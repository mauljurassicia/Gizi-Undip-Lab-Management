<?php

namespace App\Http\Controllers;

use App\DataTables\BorrowingDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Http\Requests;
use App\Http\Requests\CreateBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Repositories\BorrowingRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Group;
use App\Models\LogBook;
use App\Repositories\RoomRepository;
use App\User;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BorrowingController extends AppBaseController
{
    /** @var  BorrowingRepository */
    private $borrowingRepository;

    /** @var  RoomRepository */
    private $roomRepository;

    public function __construct(
        BorrowingRepository $borrowingRepo,
        RoomRepository $roomRepo
    ) {
        $this->middleware('auth');
        $this->middleware('can:borrowing-edit', ['only' => ['edit']]);
        $this->middleware('can:borrowing-store', ['only' => ['store']]);
        $this->middleware('can:borrowing-show', ['only' => ['show']]);
        $this->middleware('can:borrowing-update', ['only' => ['update']]);
        $this->middleware('can:borrowing-delete', ['only' => ['delete']]);
        $this->middleware('can:borrowing-create', ['only' => ['create']]);
        $this->borrowingRepository = $borrowingRepo;
        $this->roomRepository = $roomRepo;
    }

    /**
     * Display a listing of the Borrowing.
     *
     * @param BorrowingDataTable $borrowingDataTable
     * @return Response
     */
    public function index()
    {
        $rooms = $this->roomRepository->get();
        return view('borrowings.index')->with('rooms', $rooms);
    }


    /**
     * 
     * Get the list of equipment by room
     * 
     * @return Response
     */
    public function getEquipmentByRoom(int $room)
    {
        $room = $this->roomRepository->findWithoutFail($room);

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Equipment found', $room->equipment()->withPivot('quantity')->get())->send();
    }


    /**
     * 
     * Get the quantity of equipment by room and equipment type
     * 
     * @return Response
     */
    public function getQuantityByRoomAndEquipment(int $room, int $equipment, Request $request)
    {
        $room = $this->roomRepository->findWithoutFail($room);

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }

        $equipment = $room->equipment->where('id', $equipment)->first();

        if (empty($equipment)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Equipment not found')->send();
        }

        $startDate = Carbon::parse($request->query('start_date'));
        $endDate = Carbon::parse($request->query('end_date'));

        if (! $startDate->isValid() || ! $endDate->isValid()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Start date or end date is not valid')->send();
        }

        if ($request->has('isEdit') && $request->input('isEdit') == 'true') {
            $usedQuantity = $this->borrowingRepository->getQuantityByRoomAndEquipmentExceptId($room->id, $equipment->id, $startDate, $endDate, $request->query('id'));
        } else {
            $usedQuantity = $this->borrowingRepository->getQuantityByRoomAndEquipment($room->id, $equipment->id, $startDate, $endDate);
        }



        $quantity = $room->equipment()->withPivot('quantity')->where('equipment_id', $equipment->id)->first()?->pivot->quantity;

        $remainQuantity = $quantity - $usedQuantity;

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Quantity found', $remainQuantity)->send();
    }


    public function addBorrowing(Request $request)
    {
        if (!Auth::check()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        /** @var User */
        $user = Auth::user();

        $room = $this->roomRepository->findWithoutFail($request->input('roomId'));

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }

        $equipment = $room->equipment->where('id', $request->input('equipmentId'))->first();

        if (empty($equipment)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Equipment not found')->send();
        }

        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'));


        if (! $startDate->isValid() || ! $endDate->isValid()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Start date or end date is not valid')->send();
        }


        $usedQuantity = $this->borrowingRepository->getQuantityByRoomAndEquipment($room->id, $equipment->id, $startDate, $endDate);

        $quantity = $room->equipment()->withPivot('quantity')->where('equipment_id', $equipment->id)->first()?->pivot->quantity;

        $remainQuantity = $quantity - $usedQuantity;

        if ($remainQuantity < $request->input('quantity')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Quantity is not enough')->send();
        }



        $this->borrowingRepository->create([
            'room_id' => $room->id,
            'equipment_id' => $equipment->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'quantity' => $request->input('quantity'),
            'userable_type' => $request->input('borrowerType') == 1 ? 'App\User' : 'App\Models\Group',
            'userable_id' => $request->input('borrowerType') == 1 ? $user->id : $request->input('borrowerId'),
            'activity_name' => $request->input('activityName'),
            'description' => $request->input('description'),
            'status' => 'pending'
        ]);


        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowing added')->send();
    }

    public function updateBorrowing(Request $request, $id)
    {
        if (!Auth::check()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unathorized')->send();
        }

        /** @var User */
        $user = Auth::user();

        $room = $this->roomRepository->findWithoutFail($request->input('roomId'));

        if (empty($room)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Room not found')->send();
        }

        $equipment = $room->equipment->where('id', $request->input('equipmentId'))->first();

        if (empty($equipment)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Equipment not found')->send();
        }

        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'));


        if (! $startDate->isValid() || ! $endDate->isValid()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Start date or end date is not valid')->send();
        }

        $usedQuantity = $this->borrowingRepository->getQuantityByRoomAndEquipmentExceptId($room->id, $equipment->id, $startDate, $endDate, $id);

        $quantity = $room->equipment()->withPivot('quantity')->where('equipment_id', $equipment->id)->first()?->pivot->quantity;

        $remainQuantity = $quantity - $usedQuantity;

        if ($remainQuantity < $request->input('quantity')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Quantity is not enough')->send();
        }



        $this->borrowingRepository->update([
            'room_id' => $room->id,
            'equipment_id' => $equipment->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'quantity' => $request->input('quantity'),
            'userable_type' => $request->input('borrowerType') == 1 ? 'App\User' : 'App\Models\Group',
            'userable_id' => $request->input('borrowerType') == 1 ? $user->id : $request->input('borrowerId'),
            'activity_name' => $request->input('activityName'),
            'description' => $request->input('description'),
            'status' => 'pending'
        ], $id);

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowing updated')->send();
    }

    public function getBorrowings(Request $request)
    {
        if (!Auth::check()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        /** @var User */
        $user = Auth::user();

        if ($user->hasRole('administrator') || $user->hasRole('laborant')) {
            $borrowings = $this->borrowingRepository->when($request->has("searchEquipment"), function ($query) {
                return $query->whereHas('equipment', function ($query) {
                    return $query->where('name', 'like', '%' . request('searchEquipment') . '%');
                });
            })->when($request->has("roomFilter"), function ($query) {
                return $query->where('room_id', request('roomFilter'));
            })->when($request->has("statusFilter"), function ($query) {
                return $query->where('status', request('statusFilter'));
            })->with(['room', 'equipment'])->get()->append('logBookOut')->append('logBookIn');

            return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowings found', $borrowings)->send();
        }

        $borrowings = $this->borrowingRepository->when($request->has("searchEquipment"), function ($query) {
            return $query->whereHas('equipment', function ($query) {
                return $query->where('name', 'like', '%' . request('searchEquipment') . '%');
            });
        })->when($request->has("roomFilter"), function ($query) {
            return $query->where('room_id', request('roomFilter'));
        })->when($request->has("statusFilter"), function ($query) {
            return $query->where('status', request('statusFilter'));
        })->whereHasMorph('userable', [User::class, Group::class], function ($query) use ($user) {
            $query->when($query->getModel() instanceof User, function ($q) use ($user) {
                $q->where('id', $user->id);
            })->when($query->getModel() instanceof Group, function ($q) use ($user) {
                $q->whereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('users.id', $user->id);
                });
            });
        })->with(['room', 'equipment'])->get()->append('logBookOut')->append('logBookIn');

        if(empty($borrowings)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Borrowings not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowings found', $borrowings)->send();
    }

    public function approveBorrowing($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('laborant') && !$user->hasRole('administrator')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        if (empty($borrowing)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Borrowing not found')->send();
        }

        $borrowing->status = 'approved';

        $borrowing->save();

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowing approved')->send();
    }

    public function rejectBorrowing($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('laborant') && !$user->hasRole('administrator')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        if (empty($borrowing)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Borrowing not found')->send();
        }

        $borrowing->status = 'rejected';

        $borrowing->save();

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowing rejected')->send();
    }

    public function addLogBook(Request $request, $id)
    {

        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Borrowing not found')->send();
        }

        $datetime = Carbon::parse($request->input('date') . ' ' . $request->input('time'));

        if (! $datetime->isValid()) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Date is not valid')->send();
        }

        $logBook = new LogBook([
            'report' => $request->input('report'),
            'time' => $datetime,
            'type' => $request->input('type'),
        ]);

        $userId = auth()->id(); // assuming this is how you get the current user ID

        $userSchedule = $borrowing->userable()
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('userable_type', 'App\Models\User')
                        ->where('id', $userId);
                })
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('userable_type', 'App\Models\Group')
                            ->whereHas('users', function ($groupQuery) use ($userId) {
                                $groupQuery->where('users.id', $userId);
                            });
                    });
            })
            ->first();

        if (!$userSchedule) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        $logBook->userable()->associate($userSchedule);
        $logBook->logbookable()->associate($borrowing);

        $logBook->save();

        if ($request->input('type') == 'out') {
            $borrowing->status = 'returned';
            $borrowing->save();
            $borrowing->return_quantity = $request->input('quantity');
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Log book added')->send();
    }


    public function getGroup()
    {
        if (!Auth::check()) {
            return redirect(route('login'));
        }

        /** @var User */
        $user = Auth::user();

        $groups = $user->groups;

        if (empty($groups)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Group not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Group found', $groups)->send();
    }

    /**
     * Show the form for creating a new Borrowing.
     *
     * @return Response
     */
    public function create()
    {
        return view('borrowings.create');
    }

    /**
     * Store a newly created Borrowing in storage.
     *
     * @param CreateBorrowingRequest $request
     *
     * @return Response
     */
    public function store(CreateBorrowingRequest $request)
    {
        $input = $request->all();

        $borrowing = $this->borrowingRepository->create($input);

        Flash::success('Borrowing saved successfully.');
        return redirect(route('borrowings.index'));
    }

    /**
     * Display the specified Borrowing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        return view('borrowings.show')->with('borrowing', $borrowing);
    }

    /**
     * Show the form for editing the specified Borrowing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {


        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        return view('borrowings.edit')
            ->with('borrowing', $borrowing);
    }

    /**
     * Update the specified Borrowing in storage.
     *
     * @param  int              $id
     * @param UpdateBorrowingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBorrowingRequest $request)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        $input = $request->all();
        $borrowing = $this->borrowingRepository->update($input, $id);

        Flash::success('Borrowing updated successfully.');
        return redirect(route('borrowings.index'));
    }

    /**
     * Remove the specified Borrowing from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function deleteBorrowing($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Borrowing not found')->send();
        }

        $borrowing->logBooks()->delete();

        $this->borrowingRepository->delete($id);

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Borrowing deleted successfully')->send();
    }

    /**
     * Store data Borrowing from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $borrowing = $this->borrowingRepository->create($item->toArray());
            });
        });

        Flash::success('Borrowing saved successfully.');
        return redirect(route('borrowings.index'));
    }
}
