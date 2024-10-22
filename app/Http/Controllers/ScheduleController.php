<?php

namespace App\Http\Controllers;

use App\DataTables\ScheduleDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Repositories\ScheduleRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CourseRepository;
use App\Repositories\RoomRepository;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends AppBaseController
{
    /** @var  ScheduleRepository */
    private $scheduleRepository;

    /** @var RoomRepository */
    private $roomRepository;

    /** @var CourseRepository */
    private $courseRepository;

    public function __construct(
        ScheduleRepository $scheduleRepo,
        RoomRepository $roomRepo,
        CourseRepository $courseRepo
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

    public function getScheduleByRoomAndDate($room)
    {
        $schedules = $this->scheduleRepository->where('room_id', $room)
            ->whereDate('start_schedule', '=', request('date'))
            ->whereDate('end_schedule', '=', request('date'))->get();

        if (count($schedules) == 0) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Schedule not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Schedule found', $schedules)->send();
    }

    public function addSchedule($room, Request $request)
    {
        // if(){

        // }

    }
}
