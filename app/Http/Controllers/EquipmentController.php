<?php

namespace App\Http\Controllers;

use App\DataTables\EquipmentDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Repositories\EquipmentRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Equipment;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class EquipmentController extends AppBaseController
{
    /** @var  EquipmentRepository */
    private $equipmentRepository;

    /** @var SaveFileService */
    private $saveFileService;

    /** @var string */
    private $storage = "equipment";

    public function __construct(EquipmentRepository $equipmentRepo,
        SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:equipment-edit', ['only' => ['edit']]);
        $this->middleware('can:equipment-store', ['only' => ['store']]);
        $this->middleware('can:equipment-show', ['only' => ['show']]);
        $this->middleware('can:equipment-update', ['only' => ['update']]);
        $this->middleware('can:equipment-delete', ['only' => ['delete']]);
        $this->middleware('can:equipment-create', ['only' => ['create']]);
        $this->equipmentRepository = $equipmentRepo;
        $this->saveFileService = $saveFileService;
    }

    /**
     * Display a listing of the Equipment.
     *
     * @param EquipmentDataTable $equipmentDataTable
     * @return Response
     */
    public function index(EquipmentDataTable $equipmentDataTable)
    {
        return $equipmentDataTable->render('equipment.index');
    }

    /**
     * Show the form for creating a new Equipment.
     *
     * @return Response
     */
    public function create()
    {
        $typeEquipments = Equipment::$type;
        $unitTypes = Equipment::$unitTypes;
        return view('equipment.create')
        ->with('typeEquipments', $typeEquipments)
        ->with('unitTypes', $unitTypes);
    }

    /**
     * Store a newly created Equipment in storage.
     *
     * @param CreateEquipmentRequest $request
     *
     * @return Response
     */
    public function store(CreateEquipmentRequest $request)
    {
        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->handle();
        }

        $equipment = $this->equipmentRepository->create($input);





        Flash::success('Equipment saved successfully.');
        return redirect(route('equipment.index'));
    }

    /**
     * Display the specified Equipment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $equipment = $this->equipmentRepository->findWithoutFail($id);

        if (empty($equipment)) {
            Flash::error('Equipment not found');
            return redirect(route('equipment.index'));
        }

        return view('equipment.show')->with('equipment', $equipment);
    }

    /**
     * Show the form for editing the specified Equipment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        

        $equipment = $this->equipmentRepository->findWithoutFail($id);

        $typeEquipments = Equipment::$type;

        $unitTypes = Equipment::$unitTypes;

        if (empty($equipment)) {
            Flash::error('Equipment not found');
            return redirect(route('equipment.index'));
        }

        



        return view('equipment.edit')
            ->with('equipment', $equipment)
            ->with('typeEquipments', $typeEquipments)
            ->with('unitTypes', $unitTypes);
    }

    /**
     * Update the specified Equipment in storage.
     *
     * @param  int              $id
     * @param UpdateEquipmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEquipmentRequest $request)
    {
        $equipment = $this->equipmentRepository->findWithoutFail($id);

        if (empty($equipment)) {
            Flash::error('Equipment not found');
            return redirect(route('equipment.index'));
        }

        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->setModel($equipment->image)->handle();
        }
        $equipment = $this->equipmentRepository->update($input, $id);

        Flash::success('Equipment updated successfully.');
        return redirect(route('equipment.index'));
    }

    /**
     * Remove the specified Equipment from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $equipment = $this->equipmentRepository->findWithoutFail($id);

        if (empty($equipment)) {
            Flash::error('Equipment not found');
            return redirect(route('equipment.index'));
        }

        $this->equipmentRepository->delete($id);

        Flash::success('Equipment deleted successfully.');
        return redirect(route('equipment.index'));
    }

    /**
     * Store data Equipment from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $equipment = $this->equipmentRepository->create($item->toArray());
            });
        });

        Flash::success('Equipment saved successfully.');
        return redirect(route('equipment.index'));
    }
}
