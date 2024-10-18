<?php

namespace App\Http\Controllers;

use App\DataTables\TeacherDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Repositories\TeacherRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class TeacherController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;


    /** @var SaveFileService */
    private $saveFileService;


    /** @var string */
    private $storage = 'teachers';

    public function __construct(UserRepository $userRepo,
        SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:teacher-edit', ['only' => ['edit']]);
        $this->middleware('can:teacher-store', ['only' => ['store']]);
        $this->middleware('can:teacher-show', ['only' => ['show']]);
        $this->middleware('can:teacher-update', ['only' => ['update']]);
        $this->middleware('can:teacher-delete', ['only' => ['delete']]);
        $this->middleware('can:teacher-create', ['only' => ['create']]);
        $this->userRepository = $userRepo;
        $this->saveFileService = $saveFileService;
    }

    /**
     * Display a listing of the Teacher.
     *
     * @param TeacherDataTable $teacherDataTable
     * @return Response
     */
    public function index(TeacherDataTable $teacherDataTable)
    {
        return $teacherDataTable->render('teachers.index');
    }

    /**
     * Show the form for creating a new Teacher.
     *
     * @return Response
     */
    public function create()
    {
        
        return view('teachers.create');
    }

    /**
     * Store a newly created Teacher in storage.
     *
     * @param CreateTeacherRequest $request
     *
     * @return Response
     */
    public function store(CreateTeacherRequest $request)
    {
        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->handle();
        }

        $teacher = $this->userRepository->create($input);

        $teacher->assignRole('teacher');

        Flash::success('Teacher saved successfully.');
        return redirect(route('teachers.index'));
    }

    /**
     * Display the specified Teacher.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $teacher = $this->userRepository->findWithoutFail($id);

        if (empty($teacher)) {
            Flash::error('Teacher not found');
            return redirect(route('teachers.index'));
        }

        return view('teachers.show')->with('teacher', $teacher);
    }

    /**
     * Show the form for editing the specified Teacher.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        

        $teacher = $this->userRepository->findWithoutFail($id);

        if (empty($teacher)) {
            Flash::error('Teacher not found');
            return redirect(route('teachers.index'));
        }

        return view('teachers.edit')
            ->with('teacher', $teacher);
    }

    /**
     * Update the specified Teacher in storage.
     *
     * @param  int              $id
     * @param UpdateTeacherRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTeacherRequest $request)
    {
        $teacher = $this->userRepository->findWithoutFail($id);

        if (empty($teacher)) {
            Flash::error('Teacher not found');
            return redirect(route('teachers.index'));
        }

        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->setModel($teacher->image)->handle();
        }
        $teacher = $this->userRepository->update($input, $id);

        Flash::success('Teacher updated successfully.');
        return redirect(route('teachers.index'));
    }

    /**
     * Remove the specified Teacher from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $teacher = $this->userRepository->findWithoutFail($id);

        if (empty($teacher)) {
            Flash::error('Teacher not found');
            return redirect(route('teachers.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Teacher deleted successfully.');
        return redirect(route('teachers.index'));
    }

    /**
     * Store data Teacher from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $teacher = $this->userRepository->create($item->toArray());
            });
        });

        Flash::success('Teacher saved successfully.');
        return redirect(route('teachers.index'));
    }
}
