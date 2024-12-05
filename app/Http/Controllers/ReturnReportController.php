<?php

namespace App\Http\Controllers;

use App\DataTables\ReturnReportDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Http\Requests\CreateReturnReportRequest;
use App\Http\Requests\UpdateReturnReportRequest;
use App\Repositories\ReturnReportRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BrokenEquipmentRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReturnReportController extends AppBaseController
{
    /** @var  ReturnReportRepository */
    private $returnReportRepository;

    /** @var  BrokenEquipmentRepository */
    private $brokenEquipmentRepository;

    /** @var  SaveFileService */
    private $saveFileService;

    public function __construct(
        ReturnReportRepository $returnReportRepo,
        BrokenEquipmentRepository $brokenEquipmentRepo,
        SaveFileService $saveFileService
    ) {
        $this->middleware('auth');
        $this->middleware('can:returnReport-edit', ['only' => ['edit']]);
        $this->middleware('can:returnReport-store', ['only' => ['store']]);
        $this->middleware('can:returnReport-show', ['only' => ['show']]);
        $this->middleware('can:returnReport-update', ['only' => ['update']]);
        $this->middleware('can:returnReport-delete', ['only' => ['delete']]);
        $this->middleware('can:returnReport-create', ['only' => ['create']]);
        $this->returnReportRepository = $returnReportRepo;
        $this->brokenEquipmentRepository = $brokenEquipmentRepo;
        $this->saveFileService = $saveFileService;
    }

    /**
     * Display a listing of the ReturnReport.
     *
     * @param ReturnReportDataTable $returnReportDataTable
     * @return Response
     */
    public function index(ReturnReportDataTable $returnReportDataTable)
    {
        return $returnReportDataTable->render('return_reports.index');
    }

    /**
     * Show the form for creating a new ReturnReport.
     *
     * @return Response
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('administrator')) {
            $brokenEquipments = $this->brokenEquipmentRepository->whereNull('return_date')->whereDoesntHave('returnReport')->get();
        } else {
            $brokenEquipments = $this->brokenEquipmentRepository->where('user_id', $user->id)->whereNull('return_date')->whereDoesntHave('returnReport')->get();
        }


        if ($brokenEquipments->isEmpty()) {
            Flash::error('Anda tidak memiliki laporan peralatan rusak');
            return redirect(route('returnReports.index'));
        }

        return view('return_reports.create')
            ->with('brokenEquipments', $brokenEquipments);
    }

    /**
     * Store a newly created ReturnReport in storage.
     *
     * @param CreateReturnReportRequest $request
     *
     * @return Response
     */
    public function store(CreateReturnReportRequest $request)
    {
        $input = $request->all();

        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($input['broken_equipment_id']);

        if (empty($brokenEquipment)) {
            Flash::error('Laporan peralatan rusak tidak ditemukan');
            return redirect(route('returnReports.index'));
        }

        /** @var User $user */
        $user = Auth::user();

        if ($brokenEquipment->user_id != $user->id && !$user->hasRole('laborant') && !$user->hasRole('administrator')) {
            Flash::error('Anda tidak memiliki akses untuk laporan peralatan ini');
            return redirect(route('returnReports.index'));
        }

        if ($request->hasFile('image')) {
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage('returnReport')->handle();
        }

        $returnReport = $this->returnReportRepository->create($input);
        $this->brokenEquipmentRepository->where('id', $returnReport->broken_equipment_id)->update(['return_date' => $returnReport->return_date]);

        if ($user->hasRole('laborant') || $user->hasRole('administrator')) {
            $returnReport->update(['status' => 'approved']);
        }



        Flash::success('Return Report saved successfully.');
        return redirect(route('returnReports.index'));
    }

    /**
     * Display the specified ReturnReport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            Flash::error('Return Report not found');
            return redirect(route('returnReports.index'));
        }

        return view('return_reports.show')->with('returnReport', $returnReport);
    }

    /**
     * Show the form for editing the specified ReturnReport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('administrator')) {
            $brokenEquipments = $this->brokenEquipmentRepository->whereNull('return_date')->whereDoesntHave('returnReport')->get();

        } else {
            $brokenEquipments = $this->brokenEquipmentRepository->where('user_id', $user->id)->whereNull('return_date')->whereDoesntHave('returnReport')->get();
            
        }


        if ($brokenEquipments->isEmpty()) {
            Flash::error('Anda tidak memiliki laporan peralatan rusak');
            return redirect(route('returnReports.index'));
        }


        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            Flash::error('Return Report not found');
            return redirect(route('returnReports.index'));
        }

        return view('return_reports.edit')
            ->with('returnReport', $returnReport)
            ->with('brokenEquipments', $brokenEquipments);
    }

    /**
     * Update the specified ReturnReport in storage.
     *
     * @param  int              $id
     * @param UpdateReturnReportRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReturnReportRequest $request)
    {
        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            Flash::error('Return Report not found');
            return redirect(route('returnReports.index'));
        }

        $input = $request->all();

        $brokenEquipment = $this->brokenEquipmentRepository->findWithoutFail($input['broken_equipment_id']);

        if (empty($brokenEquipment)) {
            Flash::error('Laporan peralatan rusak tidak ditemukan');
            return redirect(route('returnReports.index'));
        }

        /** @var User $user */
        $user = Auth::user();

        if ($brokenEquipment->user_id != $user->id && !$user->hasRole('laborant') && !$user->hasRole('administrator')) {
            Flash::error('Anda tidak memiliki akses untuk laporan peralatan ini');
            return redirect(route('returnReports.index'));
        }

        if ($request->hasFile('image')) {
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage('returnReport')->setModel($returnReport->image)->handle();
        }

        $returnReport = $this->returnReportRepository->update($input, $id);
        $this->brokenEquipmentRepository->where('id', $returnReport->broken_equipment_id)->update(['return_date' => $returnReport->return_date]);

        if ($user->hasRole('laborant') || $user->hasRole('administrator')) {
            $returnReport->update(['status' => 'approved']);
        }

        Flash::success('Return Report updated successfully.');
        return redirect(route('returnReports.index'));
    }

    /**
     * Remove the specified ReturnReport from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            Flash::error('Return Report not found');
            return redirect(route('returnReports.index'));
        }

        $this->returnReportRepository->delete($id);
        $this->brokenEquipmentRepository->where('id', $returnReport->broken_equipment_id)->update(['return_date' => null]);

        Flash::success('Return Report deleted successfully.');
        return redirect(route('returnReports.index'));
    }

    public function changeStatus(Request $request, $id)
    {

        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasRole('administrator') && !$user->hasRole('laborant')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_UNATENTICATED, 'Unauthorized')->send();
        }

        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Return Report not found')->send();
        }

        $returnReport->status = $request->status;
        $returnReport->save();

        $this->brokenEquipmentRepository->where('id', $returnReport->broken_equipment_id)->update(['return_date' => $returnReport->return_date]);

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Return Report updated successfully')->send();
    }
}
