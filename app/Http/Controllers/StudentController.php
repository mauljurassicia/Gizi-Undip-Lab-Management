<?php

namespace App\Http\Controllers;

use App\DataTables\StudentDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class StudentController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var SaveFileService */
    private $saveFileService;

    /** @var string */
    private $storage = 'students';

    public function __construct(UserRepository $userRepo,
        SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:student-edit', ['only' => ['edit']]);
        $this->middleware('can:student-store', ['only' => ['store']]);
        $this->middleware('can:student-show', ['only' => ['show']]);
        $this->middleware('can:student-update', ['only' => ['update']]);
        $this->middleware('can:student-delete', ['only' => ['delete']]);
        $this->middleware('can:student-create', ['only' => ['create']]);
        $this->userRepository = $userRepo;
        $this->saveFileService = $saveFileService;
    }

    /**
     * Display a listing of the Student.
     *
     * @param StudentDataTable $studentDataTable
     * @return Response
     */
    public function index(StudentDataTable $studentDataTable)
    {
        return $studentDataTable->render('students.index');
    }

    /**
     * Show the form for creating a new Student.
     *
     * @return Response
     */
    public function create()
    {
        
        return view('students.create');
    }

    /**
     * Store a newly created Student in storage.
     *
     * @param CreateStudentRequest $request
     *
     * @return Response
     */
    public function store(CreateStudentRequest $request)
    {
        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->handle();
        }

        $student = $this->userRepository->create($input);

        $student->assignRole('student');

        Flash::success('Student saved successfully.');
        return redirect(route('students.index'));
    }

    /**
     * Display the specified Student.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $student = $this->userRepository->findWithoutFail($id);

        if (empty($student)) {
            Flash::error('Student not found');
            return redirect(route('students.index'));
        }

        return view('students.show')->with('student', $student);
    }

    /**
     * Show the form for editing the specified Student.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        

        $student = $this->userRepository->findWithoutFail($id);

        if (empty($student)) {
            Flash::error('Student not found');
            return redirect(route('students.index'));
        }

        return view('students.edit')
            ->with('student', $student);
    }

    /**
     * Update the specified Student in storage.
     *
     * @param  int              $id
     * @param UpdateStudentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStudentRequest $request)
    {
        $student = $this->userRepository->findWithoutFail($id);

        if (empty($student)) {
            Flash::error('Student not found');
            return redirect(route('students.index'));
        }

        $input = $request->all();

        if($request->hasFile('image')){
            $input['image'] = $this->saveFileService->setImage($request->file('image'))->setStorage($this->storage)->setModel($student->image)->handle();
        }
        $student = $this->userRepository->update($input, $id);

        Flash::success('Student updated successfully.');
        return redirect(route('students.index'));
    }

    /**
     * Remove the specified Student from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $student = $this->userRepository->findWithoutFail($id);

        if (empty($student)) {
            Flash::error('Student not found');
            return redirect(route('students.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Student deleted successfully.');
        return redirect(route('students.index'));
    }

    /**
     * Store data Student from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $student = $this->userRepository->create($item->toArray());
            });
        });

        Flash::success('Student saved successfully.');
        return redirect(route('students.index'));
    }
}
