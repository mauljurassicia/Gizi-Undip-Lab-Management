<?php

namespace App\Http\Controllers\Webcore;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\AppBaseController;
use App\Repositories\BorrowingRepository;
use App\Repositories\EquipmentRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScheduleRepository;
use App\User;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;
use Response;
use File;


class HomeController extends AppBaseController
{
    private $userRepository;

    /** @var ScheduleRepository */
    private $scheduleRepository;

    /** @var BorrowingRepository */
    private $borrowingRepository;

    /** @var RoomRepository */
    private $roomRepository;

    /** @var EquipmentRepository */
    private $equipmentRepository;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepo,
        ScheduleRepository $scheduleRepo,
        BorrowingRepository $borrowingRepo,
        RoomRepository $roomRepo,
        EquipmentRepository $equipmentRepo    
    ) {
        $this->middleware('auth');
        $this->userRepository = $userRepo;
        $this->scheduleRepository = $scheduleRepo;
        $this->borrowingRepository = $borrowingRepo;
        $this->roomRepository = $roomRepo;
        $this->equipmentRepository = $equipmentRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $scheduleWeek = $this->scheduleRepository->getScheduleCountPerDayThisWeek();
        $scheduleDates = $this->scheduleRepository->getScheduleCountPerDayThisMonth();
        $scheduleMonths = $this->scheduleRepository->getScheduleCountPerMonthThisYear();
        $borrowingWeek = $this->borrowingRepository->getBorrowingCountPerDayThisWeek();
        $borrowingDates = $this->borrowingRepository->getBorrowingCountPerDayThisMonth();
        $borrowingMonths = $this->borrowingRepository->getBorrowingCountPerMonthThisYear();
        $teacherCount = $this->userRepository->getTeacherCount();
        $studentCount = $this->userRepository->getStudentCount();
        $guestCount = $this->userRepository->getGuestCount();
        $laborantCount = $this->userRepository->getLaborantCount();
        $totalUserCount = $this->userRepository->getTotalCountExceptAdmin();
        $roomCount = $this->roomRepository->count();
        $equipmentTotalType = $this->equipmentRepository->count();
        $equipmentTotal = $this->equipmentRepository
            ->with('room')
            ->get()
            ->sum(function ($equipment) {
                return $equipment->room->sum('pivot.quantity');
            });


        return view('home')
            ->with('scheduleWeek', $scheduleWeek)
            ->with('scheduleDates', $scheduleDates)
            ->with('scheduleMonths', $scheduleMonths)
            ->with('borrowingWeek', $borrowingWeek)
            ->with('borrowingDates', $borrowingDates)
            ->with('borrowingMonths', $borrowingMonths)
            ->with('teacherCount', $teacherCount)
            ->with('studentCount', $studentCount)
            ->with('guestCount', $guestCount)
            ->with('laborantCount', $laborantCount)
            ->with('totalUserCount', $totalUserCount)
            ->with('roomCount', $roomCount)
            ->with('equipmentTotalType', $equipmentTotalType)
            ->with('equipmentTotal', $equipmentTotal);
    }


    public function profile()
    {
        $user = Auth::user();
        return view('profile')->with('userlogin', $user);
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $fields = [
            'name' => $input['name'],
            'email' => $input['email'],
        ];

        $image = @$request->file('image');
        if (!empty($image)) {

            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:png,jpg,jpeg'
            ]);
            if ($validator->fails()) {
                return redirect('post/create')
                    ->withErrors($validator)
                    ->withInput($input);
            }
            $fields['image'] = saveImageOriginalName($image, 'users', true, $user->image);
        }

        if (!empty($input['password'])) {
            if (@$input['password'] != @$input['password2']) {
                return redirect()->back()->withInput($input)->withErrors(['message' => 'Password didnot match!']);
            }

            $fields['password'] = Hash::make($input['password']);
        }

        $this->userRepository->update($fields, $user->id);
        Flash::success('Profile updated successfully.');
        return redirect(route('profile'));
    }
}
