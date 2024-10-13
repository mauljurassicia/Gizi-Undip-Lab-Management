<?php

namespace App\Http\Controllers;

use App\DataTables\CourseDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Repositories\CourseRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class CourseController extends AppBaseController
{
    /** @var  CourseRepository */
    private $courseRepository;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:course-edit', ['only' => ['edit']]);
        $this->middleware('can:course-store', ['only' => ['store']]);
        $this->middleware('can:course-show', ['only' => ['show']]);
        $this->middleware('can:course-update', ['only' => ['update']]);
        $this->middleware('can:course-delete', ['only' => ['delete']]);
        $this->middleware('can:course-create', ['only' => ['create']]);
        $this->courseRepository = $courseRepo;
    }

    /**
     * Display a listing of the Course.
     *
     * @param CourseDataTable $courseDataTable
     * @return Response
     */
    public function index(CourseDataTable $courseDataTable)
    {
        return $courseDataTable->render('courses.index');
    }

    /**
     * Show the form for creating a new Course.
     *
     * @return Response
     */
    public function create()
    {
        

        return view('courses.create');
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param CreateCourseRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseRequest $request)
    {
        $input = $request->all();

        $course = $this->courseRepository->create($input);

        Flash::success('Course saved successfully.');
        return redirect(route('courses.index'));
    }

    /**
     * Display the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');
            return redirect(route('courses.index'));
        }

        return view('courses.show')->with('course', $course);
    }

    /**
     * Show the form for editing the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        

        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');
            return redirect(route('courses.index'));
        }

        return view('courses.edit')
            ->with('course', $course);
    }

    /**
     * Update the specified Course in storage.
     *
     * @param  int              $id
     * @param UpdateCourseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourseRequest $request)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');
            return redirect(route('courses.index'));
        }

        $input = $request->all();
        $course = $this->courseRepository->update($input, $id);

        Flash::success('Course updated successfully.');
        return redirect(route('courses.index'));
    }

    /**
     * Remove the specified Course from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');
            return redirect(route('courses.index'));
        }

        $this->courseRepository->delete($id);

        Flash::success('Course deleted successfully.');
        return redirect(route('courses.index'));
    }

    /**
     * Store data Course from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $course = $this->courseRepository->create($item->toArray());
            });
        });

        Flash::success('Course saved successfully.');
        return redirect(route('courses.index'));
    }
}
