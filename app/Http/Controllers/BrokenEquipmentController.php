<?php

namespace App\Http\Controllers;

use App\DataTables\BrokenEquipmentDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBrokenEquipmentRequest;
use App\Http\Requests\UpdateBrokenEquipmentRequest;
use App\Repositories\BrokenEquipmentRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class BrokenEquipmentController extends AppBaseController
{
    /** @var  BrokenEquipmentRepository */
    private $brokenEquipmentRepository;

    public function __construct(BrokenEquipmentRepository $brokenEquipmentRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:brokenEquipment-edit', ['only' => ['edit']]);
        $this->middleware('can:brokenEquipment-store', ['only' => ['store']]);
        $this->middleware('can:brokenEquipment-show', ['only' => ['show']]);
        $this->middleware('can:brokenEquipment-update', ['only' => ['update']]);
        $this->middleware('can:brokenEquipment-delete', ['only' => ['delete']]);
        $this->middleware('can:brokenEquipment-create', ['only' => ['create']]);
        $this->brokenEquipmentRepository = $brokenEquipmentRepo;
    }

    /**
     * Display a listing of the BrokenEquipment.
     *
     * @param BrokenEquipmentDataTable $brokenEquipmentDataTable
     * @return Response
     */
    public function index(BrokenEquipmentDataTable $brokenEquipmentDataTable)
    {
        return $brokenEquipmentDataTable->render('broken_equipments.index');
    }

    /**
     * Show the form for creating a new BrokenEquipment.
     *
     * @return Response
     */
    public function create()
    {
        

        return view('broken_equipments.create');
    }

    /**
     * Store a newly created BrokenEquipment in storage.
     *
     * @param CreateBrokenEquipmentRequest $request
     *
     * @return Response
     */
    public function store(CreateBrokenEquipmentRequest $request)
    {
        $input = $request->all();

        $brokenEquipment = $this->brokenEquipmentRepository->create($input);

        Flash::success('Broken Equipment saved successfully.');
        return redirect(route('brokenEquipments.index'));
    }

    /**
     * Display the specified BrokenEquipment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($id);

        if (empty($brokenEquipment)) {
            Flash::error('Broken Equipment not found');
            return redirect(route('brokenEquipments.index'));
        }

        return view('broken_equipments.show')->with('brokenEquipment', $brokenEquipment);
    }

    /**
     * Show the form for editing the specified BrokenEquipment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        

        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($id);

        if (empty($brokenEquipment)) {
            Flash::error('Broken Equipment not found');
            return redirect(route('brokenEquipments.index'));
        }

        return view('broken_equipments.edit')
            ->with('brokenEquipment', $brokenEquipment);
    }

    /**
     * Update the specified BrokenEquipment in storage.
     *
     * @param  int              $id
     * @param UpdateBrokenEquipmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBrokenEquipmentRequest $request)
    {
        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($id);

        if (empty($brokenEquipment)) {
            Flash::error('Broken Equipment not found');
            return redirect(route('brokenEquipments.index'));
        }

        $input = $request->all();
        $brokenEquipment = $this->brokenEquipmentRepository->update($input, $id);

        Flash::success('Broken Equipment updated successfully.');
        return redirect(route('brokenEquipments.index'));
    }

    /**
     * Remove the specified BrokenEquipment from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($id);

        if (empty($brokenEquipment)) {
            Flash::error('Broken Equipment not found');
            return redirect(route('brokenEquipments.index'));
        }

        $this->brokenEquipmentRepository->delete($id);

        Flash::success('Broken Equipment deleted successfully.');
        return redirect(route('brokenEquipments.index'));
    }

    /**
     * Store data BrokenEquipment from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $brokenEquipment = $this->brokenEquipmentRepository->create($item->toArray());
            });
        });

        Flash::success('Broken Equipment saved successfully.');
        return redirect(route('brokenEquipments.index'));
    }
}
