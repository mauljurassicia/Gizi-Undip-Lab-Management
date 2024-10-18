<?php

namespace App\Http\Controllers;

use App\DataTables\LaborantDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLaborantRequest;
use App\Http\Requests\UpdateLaborantRequest;
use App\Repositories\LaborantRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class LaborantController extends AppBaseController
{
    /** @var  LaborantRepository */
    private $laborantRepository;

    public function __construct(LaborantRepository $laborantRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:laborant-edit', ['only' => ['edit']]);
        $this->middleware('can:laborant-store', ['only' => ['store']]);
        $this->middleware('can:laborant-show', ['only' => ['show']]);
        $this->middleware('can:laborant-update', ['only' => ['update']]);
        $this->middleware('can:laborant-delete', ['only' => ['delete']]);
        $this->middleware('can:laborant-create', ['only' => ['create']]);
        $this->laborantRepository = $laborantRepo;
    }

    /**
     * Display a listing of the Laborant.
     *
     * @param LaborantDataTable $laborantDataTable
     * @return Response
     */
    public function index(LaborantDataTable $laborantDataTable)
    {
        return $laborantDataTable->render('laborants.index');
    }

    /**
     * Show the form for creating a new Laborant.
     *
     * @return Response
     */
    public function create()
    {
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        return view('laborants.create')
            ->with('', $)
            ->with('', $);
    }

    /**
     * Store a newly created Laborant in storage.
     *
     * @param CreateLaborantRequest $request
     *
     * @return Response
     */
    public function store(CreateLaborantRequest $request)
    {
        $input = $request->all();

        $laborant = $this->laborantRepository->create($input);

        Flash::success('Laborant saved successfully.');
        return redirect(route('laborants.index'));
    }

    /**
     * Display the specified Laborant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $laborant = $this->laborantRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        return view('laborants.show')->with('laborant', $laborant);
    }

    /**
     * Show the form for editing the specified Laborant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $ = \App\Models\::all();
        $ = \App\Models\::all();
        

        $laborant = $this->laborantRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        return view('laborants.edit')
            ->with('laborant', $laborant)
            ->with('', $)
            ->with('', $);
    }

    /**
     * Update the specified Laborant in storage.
     *
     * @param  int              $id
     * @param UpdateLaborantRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLaborantRequest $request)
    {
        $laborant = $this->laborantRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        $input = $request->all();
        $laborant = $this->laborantRepository->update($input, $id);

        Flash::success('Laborant updated successfully.');
        return redirect(route('laborants.index'));
    }

    /**
     * Remove the specified Laborant from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $laborant = $this->laborantRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        $this->laborantRepository->delete($id);

        Flash::success('Laborant deleted successfully.');
        return redirect(route('laborants.index'));
    }

    /**
     * Store data Laborant from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $laborant = $this->laborantRepository->create($item->toArray());
            });
        });

        Flash::success('Laborant saved successfully.');
        return redirect(route('laborants.index'));
    }
}
