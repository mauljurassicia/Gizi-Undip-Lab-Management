<?php

namespace App\Http\Controllers;

use App\DataTables\ScheduleDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Repositories\ScheduleRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Schedule;
use App\Repositories\CourseRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Group;

class ScheduleController extends AppBaseController
{
    /** @var  ScheduleRepository */
    private $scheduleRepository;

    /** @var RoomRepository */
    private $roomRepository;

    /** @var CourseRepository */
    private $courseRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var GroupRepository */
    private $groupRepository;



    public function __construct(
        ScheduleRepository $scheduleRepo,
        RoomRepository $roomRepo,
        CourseRepository $courseRepo,
        UserRepository $userRepository,
        GroupRepository $groupRepository,
    ) {
        $this->middleware('auth');
        $this->middleware('can:schedule-edit', ['only' => ['edit']]);
        $this->middleware('can:schedule-store', ['only' => ['store']]);
        $this->middleware('can:schedule-show', ['only' => ['show']]);
        $this->middleware('can:schedule-update', ['only' => ['update']]);
        $this->middleware('can:schedule-delete', ['only' => ['delete']]);
        $this->middleware('can:schedule-create', ['only' => ['create']]);
        $this->scheduleRepository = $scheduleRepo;
        $this->roomRepository = $roomRepo;
        $this->courseRepository = $courseRepo;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Display a listing of the Schedule.
     *
     * @param ScheduleDataTable $scheduleDataTable
     * @return Response
     */
    public function index(ScheduleDataTable $scheduleDataTable)
    {
        return $scheduleDataTable->render('schedules.index');
    }

    /**
     * Show the form for editing the specified Schedule.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $room = $this->roomRepository->findWithoutFail($id);
        $courses = $this->courseRepository->all()->pluck('name', 'id');

        $groups = $user->groups()->get();



        if (empty($room)) {
            Flash::error('Schedule not found');
            return redirect(route('schedules.index'));
        }

        return view('schedules.edit')
            ->with('room', $room)
            ->with('courses', $courses)
            ->with('groups', $groups);
    }

    /**
     * Remove the specified Schedule from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            Flash::error('Schedule not found');
            return redirect(route('schedules.index'));
        }

        $this->scheduleRepository->delete($id);

        Flash::success('Schedule deleted successfully.');
        return redirect(route('schedules.index'));
    }

    public function getOperationalHoursByRoomAndDate($room, Request $request)
    {

        $room = $this->roomRepository->findWithoutFail($room);

        if ($request->query('date') == '' || $request->query('date') == null) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Date is required')->send();
        }

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }
        if (! Carbon::createFromFormat('Y-m-d', $request->query('date'))->isValid()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Date is not valid')->send();
        }

        $dayName = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu'
        ][date('l', strtotime($request->query('date')))];


        $operationalHours = json_decode($room->operational_days);

        if (! isset($operationalHours->$dayName)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Operational hours not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Operational hours found', $operationalHours->$dayName)->send();
    }

    public function getScheduleByRoomAndDate($room, Request $request)
    {

        if (!$request->query('date')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Date is required')->send();
        }
        $schedules = $this->scheduleRepository->where('room_id', $room)->whereDate('start_schedule', $request->query('date'))
            ->whereDate('end_schedule', $request->query('date'))->with('course')->with('groups')->with('users')->get();

        $schedules->each(function ($schedule) {
            $schedule->weeks = $this->scheduleRepository->where('grouped_schedule_code', $schedule->grouped_schedule_code)->whereNotNull('grouped_schedule_code')->count();
        });

        if (count($schedules) == 0) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Schedule not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Schedule found', $schedules)->send();
    }

    public function addSchedule($room, Request $request)
    {
        $room = $this->roomRepository->findWithoutFail($room);

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }

        $input = $request->all();
        $dates = null;
        $typeModel = null;
        $typeId = [];
        $scheduleType = null;

        $dateErrorResponse = $this->handleDate($dates, $input, $scheduleType);


        if ($dateErrorResponse instanceof JsonResponse) {
            return $dateErrorResponse;
        }

        $modelErrorResponse = $this->handleTypeModel($typeModel, $typeId, $input);

        if ($modelErrorResponse instanceof JsonResponse) {
            return $modelErrorResponse;
        }

        $capacityErrorResponse = $this->handleCountCapacity($typeModel, $typeId, $room);

        if ($capacityErrorResponse instanceof JsonResponse) {
            return $capacityErrorResponse;
        }

        $successAssigns = 0;

        if (is_array($dates)) {
            $input['grouped_schedule_code'] = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 7));
            foreach ($dates as $item) {

                $createScheduleErrorResponse = $this->createSchedule($input, $item, $room, $typeModel, $typeId, $scheduleType);

                if ($createScheduleErrorResponse  instanceof JsonResponse) {
                    return $createScheduleErrorResponse;
                }

                if ($createScheduleErrorResponse) {
                    $successAssigns++;
                }
            }
        } else {
            $createScheduleErrorResponse = $this->createSchedule($input, $dates, $room, $typeModel, $typeId, $scheduleType);

            if ($createScheduleErrorResponse  instanceof JsonResponse) {
                return $createScheduleErrorResponse;
            }

            if ($createScheduleErrorResponse) {
                $successAssigns++;
            }
        }

        if ($successAssigns == 0) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Jadwal tidak berhasil ditambahkan')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Jadwal berhasil ditambahkan')->send();
    }

    private function handleDate(&$dates, $input, &$scheduleType)
    {
        if ($input['type_schedule'] == 0) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Tipe Kunjungan harus diisi')->send();
        } else if ($input['type_schedule'] == 1) {
            $dates = Carbon::createFromFormat('Y-m-d', $input['date']);
        } else if ($input['type_schedule'] == 2) {
            if (!$input['weeks']) {
                return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Jumlah minggu harus diisi')->send();
            }

            $scheduleType = 'weekly';

            $dates = [];
            $weeks = $input['weeks'];
            $date = Carbon::createFromFormat('Y-m-d', $input['date']);

            for ($i = 0; $i < $weeks; $i++) {
                $dates[] = $date->copy()->addWeeks($i);
            }
        } else if ($input['type_schedule'] == 3) {
            if (!$input['weeks']) {
                return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Jumlah bulan harus diisi')->send();
            }

            $scheduleType = 'monthly';

            $dates = [];
            $weeks = $input['weeks'];
            $date = Carbon::createFromFormat('Y-m-d', $input['date']);

            for ($i = 0; $i < $weeks; $i++) {
                $dates[] = $date->copy()->addMonths($i);
            }
        } else if ($input['type_schedule'] == 4) {
            if (!$input['weeks']) {
                return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Jumlah hari harus diisi')->send();
            }

            $scheduleType = 'series';

            $dates = [];
            $weeks = $input['weeks'];
            $date = Carbon::createFromFormat('Y-m-d', $input['date']);

            for ($i = 0; $i < $weeks; $i++) {
                $dates[] = $date->copy()->addDays($i);
            }
        } else {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Tipe Kunjungan harus diisi')->send();
        }
    }

    private function handleTypeModel(&$typeModel, &$typeId, $input)
    {
        if ($input['type_model'] == 0) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Tipe Pengunjung harus diisi')->send();
        } else if ($input['type_model'] == 1) {
            $typeModel = 'App\User';

            /** @var User */
            $user = Auth::user();
            if (!$user->hasRole('administrator') && !$user->hasRole('laborant')) {
                $typeId = [Auth::user()->id];
            } else {
                if (!$input['type_id']) {
                    return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Pengunjung harus diisi')->send();
                }
                $typeId = $input['type_id'];
            }
        } else if ($input['type_model'] == 2) {
            if (!$input['type_id']) {
                return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Pengunjung harus diisi')->send();
            }

            $typeModel = 'App\model\Group';
            $typeId = $input['type_id'];
        } else {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Tipe Pengunjung tidak valid')->send();
        }
    }

    private function createSchedule($input, $date, $room, $typeModel, $typeId, $scheduleType)
    {

        if (!$input['start_time'] || !$input['end_time']) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Waktu mulai dan waktu selesai harus diisi')->send();
        }

        if ($input['start_time'] > $input['end_time']) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Waktu mulai harus lebih kecil dari waktu selesai')->send();
        }

        $dayName = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu'
        ][date('l', strtotime($date))];


        $operationalHours = json_decode($room->operational_days);

        if (!isset($operationalHours->$dayName)) {
            return false;
        }

        $operationalHours = $operationalHours->$dayName;

        if ($input['start_time'] < $operationalHours->start || $input['end_time'] > $operationalHours->end) {
            return false;
        }

        if ($input['end_time'] < $operationalHours->start && $input['start_time'] > $operationalHours->end) {
            return false;
        }


        $start = $date->copy()->setTimeFromTimeString($input['start_time']);
        $end = $date->copy()->setTimeFromTimeString($input['end_time']);

        $existingSchedule = Schedule::where('room_id', $room->id)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($query) use ($start, $end) {
                    $query->where('start_schedule', '<', $end)
                        ->where('end_schedule', '>', $start);
                })->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_schedule', '<', $start)
                        ->where('end_schedule', '>', $end);
                });
            })->exists();

        if ($existingSchedule) {
            return false;
        }

        $schedule = new Schedule();
        $schedule->room_id = $room->id;
        $schedule->start_schedule = $start;
        $schedule->name = $input['name'];
        $schedule->end_schedule = $end;

        if (@$input['course_id'] && !($input['course_id'] == 'null') && !($input['course_id'] == '0')) {
            $schedule->course_id = $input['course_id'];
        }

        if (@$input['associated_info']) {
            $schedule->associated_info = $input['associated_info'];
        }

        if ($scheduleType) {
            $schedule->schedule_type = $scheduleType;
        }

        if (@$input['grouped_schedule_code']) {
            $schedule->grouped_schedule_code = $input['grouped_schedule_code'];
        }

        /** @var User */
        $user = Auth::user();
        if ($user->hasRole('administrator') || $user->hasRole('laborant')) {
            $schedule->status = 'approved';
        }

        $schedule->save();

        foreach ($typeId as $id) {
            if ($typeModel == 'App\User') {
                $schedule->users()->attach($id);
            } else if ($typeModel == 'App\model\Group') {
                $schedule->groups()->attach($id);
            }
        }

        return true;
    }


    private function handleCountCapacity($typeModel, $typeId, $room): JsonResponse | bool
    {
        $guestCount = 0;
        if ($typeModel == 'App\User') {
            $guestCount = $this->userRepository->whereIn('id', $typeId)->count();
        } elseif ($typeModel == 'App\model\Group') {
            $guestCount = $this->groupRepository->whereIn('id', $typeId)->withCount('users')->get()->sum('users_count');
        } else {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Type model is not valid')->send();
        }

        if ($guestCount > $room->volume) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Guest count is more than capacity')->send();
        }

        return false;
    }

    public function deleteSchedule(Request $request, $id)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Schedule not found')->send();
        }

        if (!is_null($request->query('group') && $request->query('group') == '1')) {
            $this->scheduleRepository->where('grouped_schedule_code', $schedule->grouped_schedule_code)->delete();
        } else {
            $schedule->delete();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Schedule deleted successfully')->send();
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Schedule not found')->send();
        }

        $input = $request->all();

        if (!$input['start_time'] || !$input['end_time']) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Waktu mulai dan waktu selesai harus diisi')->send();
        }


        if ($input['start_time'] > $input['end_time']) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Start time must be less than end time')->send();
        }

        $scheduleDate = Carbon::parse($schedule->start_schedule);
        $start = $scheduleDate->copy()->setTimeFromTimeString($input['start_time']);
        $end = $scheduleDate->copy()->setTimeFromTimeString($input['end_time']);

        $scheduleRoom = $schedule->room_id;

        $existingSchedule = Schedule::where('room_id', $scheduleRoom)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($query) use ($start, $end) {
                    $query->where('start_schedule', '<', $end)
                        ->where('end_schedule', '>', $start);
                })->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_schedule', '<', $start)
                        ->where('end_schedule', '>', $end);
                });
            })->where('id', '!=', $schedule->id)->exists();

        if ($existingSchedule) {
            return false;
        }

        $schedule->start_schedule = $start;
        $schedule->end_schedule = $end;
        $schedule->name = $input['name'];
        $schedule->course_id = is_numeric($input['course_id']) ? $input['course_id'] : null;
        $schedule->associated_info = $input['associated_info'];
        $schedule->save();

        if($input['type_model'] !=1 && $input['type_model'] !=2){
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Tipe Pengunjung harus diisi')->send();
        }elseif($input['type_model'] == 1){
            $typeId = $input['type_id'];
            $schedule->users()->detach();
            $schedule->groups()->detach();
            $schedule->users()->attach($typeId);
        }elseif($input['type_model'] == 2){
            $typeId = $input['type_id'];
            $schedule->users()->detach();
            $schedule->groups()->detach();
            $schedule->groups()->attach($typeId);
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Schedule updated successfully')->send();


    }
}
