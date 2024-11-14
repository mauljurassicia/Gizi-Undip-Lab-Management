<?php

namespace App\Http\Controllers;

use App\DataTables\GroupDataTable;
use App\Enums\ResponseCodeEnum;
use App\Helpers\ResponseJson;
use App\Http\Requests;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Repositories\GroupRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GroupController extends AppBaseController
{
    /** @var  GroupRepository */
    private $groupRepository;

    /** @var CourseRepository */
    private $courseRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SaveFileService */
    private $saveFileService;

    /** @var string */
    private $storage = 'group';

    public function __construct(
        GroupRepository $groupRepo,
        CourseRepository $courseRepo,
        UserRepository $userRepo,
        SaveFileService $saveFileService
    ) {
        $this->middleware('auth');
        $this->middleware('can:group-edit', ['only' => ['edit']]);
        $this->middleware('can:group-store', ['only' => ['store']]);
        $this->middleware('can:group-show', ['only' => ['show']]);
        $this->middleware('can:group-update', ['only' => ['update']]);
        $this->middleware('can:group-delete', ['only' => ['delete']]);
        $this->middleware('can:group-create', ['only' => ['create']]);
        $this->groupRepository = $groupRepo;
        $this->courseRepository = $courseRepo;
        $this->userRepository = $userRepo;
        $this->saveFileService = $saveFileService;
    }

    /**
     * Display a listing of the Group.
     *
     * @param GroupDataTable $groupDataTable
     * @return Response
     */
    public function index(GroupDataTable $groupDataTable)
    {
        return $groupDataTable->render('groups.index');
    }

    /**
     * Show the form for creating a new Group.
     *
     * @return Response
     */
    public function create()
    {
        $courses = $this->courseRepository->all()->pluck('name', 'id');
        return view('groups.create')
            ->with('courses', $courses);
    }

    /**
     * Store a newly created Group in storage.
     *
     * @param CreateGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('thumbnail')) {
            $input['thumbnail'] = $this->saveFileService->setImage($request->file('thumbnail'))->setStorage($this->storage)->handle();
        }

        $members = $input['member_id'];
        unset($input['member_id']);

        $group = $this->groupRepository->create($input);

        if ($members) {
            $group->users()->sync($members);
        }

        Flash::success('Group saved successfully.');
        return redirect(route('groups.index'));
    }

    /**
     * Display the specified Group.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');
            return redirect(route('groups.index'));
        }

        return view('groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified Group.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {


        $group = $this->groupRepository->findWithoutFail($id);
        $courses = $this->courseRepository->all()->pluck('name', 'id');

        if (empty($group)) {
            Flash::error('Group not found');
            return redirect(route('groups.index'));
        }

        return view('groups.edit')
            ->with('group', $group)
            ->with('courses', $courses);
    }

    /**
     * Update the specified Group in storage.
     *
     * @param  int              $id
     * @param UpdateGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupRequest $request)
    {
        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');
            return redirect(route('groups.index'));
        }

        $input = $request->all();

        $members = $input['member_id'];
        unset($input['member_id']);

        if ($request->hasFile('thumbnail')) {
            $input['thumbnail'] = $this->saveFileService->setImage($request->file('thumbnail'))->setStorage($this->storage)->setModel($group->thumbnail)->handle();
        }
        $group = $this->groupRepository->update($input, $id);

        if ($members) {
            $group->users()->sync($members);
        }

        Flash::success('Group updated successfully.');
        return redirect(route('groups.index'));
    }

    /**
     * Remove the specified Group from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');
            return redirect(route('groups.index'));
        }

        $this->groupRepository->delete($id);

        Flash::success('Group deleted successfully.');
        return redirect(route('groups.index'));
    }

    public function memberSuggestion(Request $request)
    {
        if (!$request->has('search')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Search term is required')->send();
        }

        $search = $request->get('search');

        $users = $this->userRepository->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhereHas('roles', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('identity_number', 'like', '%' . $search . '%')->with('roles')->get();

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Success', $users)->send();
    }

    /**
     * Store data Group from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $group = $this->groupRepository->create($item->toArray());
            });
        });

        Flash::success('Group saved successfully.');
        return redirect(route('groups.index'));
    }

    public function getMembers($id)
    {
        $users = $this->groupRepository->findWithoutFail($id)->users;
        if (!$users) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_NOT_FOUND, 'Group not found')->send();
        }
        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Success', $users)->send();
    }


    public function groupSuggestion(Request $request)
    {
        if (!$request->has('search')) {
            return ResponseJson::make(ResponseCodeEnum::STATUS_BAD_REQUEST, 'Search term is required')->send();
        }


        $groups = $this->groupRepository->where('name', 'like', '%' . $request->get('search') . '%')
            ->when($request->has('course_id'), function ($query) use ($request) {
                $query->where('course_id', $request->get('course_id'));
            })->with('course')->get();

        return ResponseJson::make(ResponseCodeEnum::STATUS_OK, 'Success', $groups)->send();
    }
}
