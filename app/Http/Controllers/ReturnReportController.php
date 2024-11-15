<?php

namespace App\Http\Controllers;

use App\DataTables\ReturnReportDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateReturnReportRequest;
use App\Http\Requests\UpdateReturnReportRequest;
use App\Repositories\ReturnReportRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class ReturnReportController extends AppBaseController
{
    /** @var  ReturnReportRepository */
    private $returnReportRepository;

    public function __construct(ReturnReportRepository $returnReportRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:returnReport-edit', ['only' => ['edit']]);
        $this->middleware('can:returnReport-store', ['only' => ['store']]);
        $this->middleware('can:returnReport-show', ['only' => ['show']]);
        $this->middleware('can:returnReport-update', ['only' => ['update']]);
        $this->middleware('can:returnReport-delete', ['only' => ['delete']]);
        $this->middleware('can:returnReport-create', ['only' => ['create']]);
        $this->returnReportRepository = $returnReportRepo;
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
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        return view('return_reports.create')
            ->with('', $)
            ->with('', $)
            ->with('', $);
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

        $returnReport = $this->returnReportRepository->create($input);

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
        
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        $returnReport = $this->returnReportRepository->findWithoutFail($id);

        if (empty($returnReport)) {
            Flash::error('Return Report not found');
            return redirect(route('returnReports.index'));
        }

        return view('return_reports.edit')
            ->with('returnReport', $returnReport)
            ->with('', $)
            ->with('', $)
            ->with('', $);
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
        $returnReport = $this->returnReportRepository->update($input, $id);

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

        Flash::success('Return Report deleted successfully.');
        return redirect(route('returnReports.index'));
    }

    /**
     * Store data ReturnReport from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $returnReport = $this->returnReportRepository->create($item->toArray());
            });
        });

        Flash::success('Return Report saved successfully.');
        return redirect(route('returnReports.index'));
    }
}
