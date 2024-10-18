<?php

namespace App\Http\Controllers;

use App\DataTables\LaborantDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLaborantRequest;
use App\Http\Requests\UpdateLaborantRequest;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LaborantController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var  SaveFileService */
    private $saveFileService;

    /** @var string */
    private $storage = 'laborants.';

    public function __construct(
        UserRepository $userRepo,
        SaveFileService $saveFileService
    ) {
        $this->middleware('auth');
        $this->middleware('can:laborant-edit', ['only' => ['edit']]);
        $this->middleware('can:laborant-store', ['only' => ['store']]);
        $this->middleware('can:laborant-show', ['only' => ['show']]);
        $this->middleware('can:laborant-update', ['only' => ['update']]);
        $this->middleware('can:laborant-delete', ['only' => ['delete']]);
        $this->middleware('can:laborant-create', ['only' => ['create']]);
        $this->userRepository = $userRepo;
        $this->saveFileService = $saveFileService;
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


        return view('laborants.create');
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

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->handle();
        }

        $laborant = $this->userRepository->create($input);

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
        $laborant = $this->userRepository->findWithoutFail($id);

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

        $laborant = $this->userRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        return view('laborants.edit')
            ->with('laborant', $laborant);
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
        $laborant = $this->userRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setModel($laborant->image)->setStorage($this->storage)->handle();
        }

        $laborant = $this->userRepository->update($input, $id);

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
        $laborant = $this->userRepository->findWithoutFail($id);

        if (empty($laborant)) {
            Flash::error('Laborant not found');
            return redirect(route('laborants.index'));
        }

        $this->userRepository->delete($id);

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
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $laborant = $this->userRepository->create($item->toArray());
            });
        });

        Flash::success('Laborant saved successfully.');
        return redirect(route('laborants.index'));
    }
}
