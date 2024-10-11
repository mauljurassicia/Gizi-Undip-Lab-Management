<?php

namespace App\Http\Controllers;

use App\DataTables\RoomDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Http\Requests;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Repositories\RoomRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Equipment;
use App\Models\Room;
use App\Repositories\EquipmentRepository;
use App\Repositories\UserRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class RoomController extends AppBaseController
{
    /** @var  RoomRepository */
    private $roomRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SaveFileService */
    private $saveFileService;

    /** @var EquipmentRepository */
    private $equipmentRepository;

    private $storage = 'rooms';

    public function __construct(RoomRepository $roomRepo,
        UserRepository $userRepo,
        SaveFileService $saveFileService,
        EquipmentRepository $equipmentRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:room-edit', ['only' => ['edit']]);
        $this->middleware('can:room-store', ['only' => ['store']]);
        $this->middleware('can:room-show', ['only' => ['show']]);
        $this->middleware('can:room-update', ['only' => ['update']]);
        $this->middleware('can:room-delete', ['only' => ['delete']]);
        $this->middleware('can:room-create', ['only' => ['create']]);
        $this->roomRepository = $roomRepo;
        $this->userRepository = $userRepo;
        $this->saveFileService = $saveFileService;
        $this->equipmentRepository = $equipmentRepo;
    }

    /**
     * Display a listing of the Room.
     *
     * @param RoomDataTable $roomDataTable
     * @return Response
     */
    public function index(RoomDataTable $roomDataTable)
    {
        return $roomDataTable->render('rooms.index');
    }

    /**
     * Show the form for creating a new Room.
     *
     * @return Response
     */
    public function create()
    {
        $typeRooms = Room::$types;
        $pic = $this->userRepository->role('Laborant')->get();
        return view('rooms.create')
        ->with('typeRooms', $typeRooms)
        ->with('pic', $pic);
    }

    /**
     * Store a newly created Room in storage.
     *
     * @param CreateRoomRequest $request
     *
     * @return Response
     */
    public function store(CreateRoomRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->handle();
        }
        
        if (isset($input['operational_days'])) {
            $input['operational_days'] = json_encode($input['operational_days'], true);
        }

        $room = $this->roomRepository->create($input);

        Flash::success('Room saved successfully.');
        return redirect(route('rooms.index'));
    }

    /**
     * Display the specified Room.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $room = $this->roomRepository->findWithoutFail($id);
        $pic = $this->userRepository->role('Laborant')->get();
        $typeRooms = Room::$types;

        if (empty($room)) {
            Flash::error('Room not found');
            return redirect(route('rooms.index'));
        }

        return view('rooms.show')->with('room', $room)
        ->with('typeRooms', $typeRooms)
        ->with('pic', $pic);
    }

    /**
     * Show the form for editing the specified Room.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        

        $room = $this->roomRepository->findWithoutFail($id);
        $typeRooms = Room::$types;
        $pic = $this->userRepository->role('Laborant')->get();

        if (empty($room)) {
            Flash::error('Room not found');
            return redirect(route('rooms.index'));
        }

        return view('rooms.edit')
            ->with('room', $room)
            ->with('typeRooms', $typeRooms)
            ->with('pic', $pic);
    }

    /**
     * Update the specified Room in storage.
     *
     * @param  int              $id
     * @param UpdateRoomRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoomRequest $request)
    {
        $room = $this->roomRepository->findWithoutFail($id);

        if (empty($room)) {
            Flash::error('Room not found');
            return redirect(route('rooms.index'));
        }

        $input = $request->all();
        if ($request->hasFile('image')) {
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->setModel($room->image)->handle();
        }

        if (isset($input['operational_days'])) {
            $input['operational_days'] = json_encode($input['operational_days'], true);
        }
        
        $room = $this->roomRepository->update($input, $id);

        Flash::success('Room updated successfully.');
        return redirect(route('rooms.index'));
    }

    /**
     * Remove the specified Room from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $room = $this->roomRepository->findWithoutFail($id);

        if (empty($room)) {
            Flash::error('Room not found');
            return redirect(route('rooms.index'));
        }

        $this->roomRepository->delete($id);

        Flash::success('Room deleted successfully.');
        return redirect(route('rooms.index'));
    }

    /**
     * Store data Room from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $room = $this->roomRepository->create($item->toArray());
            });
        });

        Flash::success('Room saved successfully.');
        return redirect(route('rooms.index'));
    }

    public function getEquipment(Request $request){
        $this->validate($request, [
            'type' => 'in:'.implode(',', array_keys(Equipment::$type)).',all',
        ]);

        if($request->query('search') == '' || $request->query('search') == null){
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Search cannot be empty')->send();
        }



        if ($request->type == 'all' || !isset($request->type) || $request->type == null) {
            $equipments = $this->equipmentRepository->where('name', 'like', '%'.$request->query('search').'%')->get();

        } else {
            $equipments = $this->equipmentRepository->where('type', $request->type)->get();
        }

        if(count($equipments) == 0){
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Equipment not found')->send();
        }

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Equipment found', $equipments)->send();
    }
}
