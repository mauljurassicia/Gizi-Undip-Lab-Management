<?php

namespace App\Http\Controllers;

use App\DataTables\ScheduleDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Repositories\ScheduleRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class ScheduleController extends AppBaseController
{
    /** @var  ScheduleRepository */
    private $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:schedule-edit', ['only' => ['edit']]);
        $this->middleware('can:schedule-store', ['only' => ['store']]);
        $this->middleware('can:schedule-show', ['only' => ['show']]);
        $this->middleware('can:schedule-update', ['only' => ['update']]);
        $this->middleware('can:schedule-delete', ['only' => ['delete']]);
        $this->middleware('can:schedule-create', ['only' => ['create']]);
        $this->scheduleRepository = $scheduleRepo;
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
     * Show the form for creating a new Schedule.
     *
     * @return Response
     */
    public function create()
    {
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        return view('schedules.create')
            ->with('', $)
            ->with('', $);
    }

    /**
     * Store a newly created Schedule in storage.
     *
     * @param CreateScheduleRequest $request
     *
     * @return Response
     */
    public function store(CreateScheduleRequest $request)
    {
        $input = $request->all();

        $schedule = $this->scheduleRepository->create($input);

        Flash::success('Schedule saved successfully.');
        return redirect(route('schedules.index'));
    }

    /**
     * Display the specified Schedule.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            Flash::error('Schedule not found');
            return redirect(route('schedules.index'));
        }

        return view('schedules.show')->with('schedule', $schedule);
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
        
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            Flash::error('Schedule not found');
            return redirect(route('schedules.index'));
        }

        return view('schedules.edit')
            ->with('schedule', $schedule)
            ->with('', $)
            ->with('', $);
    }

    /**
     * Update the specified Schedule in storage.
     *
     * @param  int              $id
     * @param UpdateScheduleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateScheduleRequest $request)
    {
        $schedule = $this->scheduleRepository->findWithoutFail($id);

        if (empty($schedule)) {
            Flash::error('Schedule not found');
            return redirect(route('schedules.index'));
        }

        $input = $request->all();
        $schedule = $this->scheduleRepository->update($input, $id);

        Flash::success('Schedule updated successfully.');
        return redirect(route('schedules.index'));
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

    /**
     * Store data Schedule from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $schedule = $this->scheduleRepository->create($item->toArray());
            });
        });

        Flash::success('Schedule saved successfully.');
        return redirect(route('schedules.index'));
    }
}
