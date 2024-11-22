<?php

namespace App\Http\Controllers;

use App\DataTables\LogBookDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLogBookRequest;
use App\Http\Requests\UpdateLogBookRequest;
use App\Repositories\LogBookRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Borrowing;
use App\Models\LogBook;
use App\Models\Schedule;
use App\Repositories\BorrowingRepository;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LogBookController extends AppBaseController
{
    /** @var  LogBookRepository */
    private $logBookRepository;

    /** @var  BorrowingRepository */
    private $borrowingRepository;

    /** @var  ScheduleRepository */
    private $scheduleRepository;

    public function __construct(
        LogBookRepository $logBookRepo,
        BorrowingRepository $borrowingRepo,
        ScheduleRepository $scheduleRepo
    ) {
        $this->middleware('auth');
        $this->middleware('can:logBook-edit', ['only' => ['edit']]);
        $this->middleware('can:logBook-store', ['only' => ['store']]);
        $this->middleware('can:logBook-show', ['only' => ['show']]);
        $this->middleware('can:logBook-update', ['only' => ['update']]);
        $this->middleware('can:logBook-delete', ['only' => ['delete']]);
        $this->middleware('can:logBook-create', ['only' => ['create']]);
        $this->logBookRepository = $logBookRepo;
        $this->borrowingRepository = $borrowingRepo;
        $this->scheduleRepository = $scheduleRepo;
    }

    /**
     * Display a listing of the LogBook.
     *
     * @param LogBookDataTable $logBookDataTable
     * @return Response
     */
    public function index(LogBookDataTable $logBookDataTable)
    {
        return $logBookDataTable->render('log_books.index');
    }

    /**
     * Show the form for creating a new LogBook.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect(route('logBooks.index'));
        }

        /** @var User $user */
        $user = Auth::user();

        // if ($user->hasRole('administrator')) {
        //     Flash::warning('You have to be logged in as a user to create a log book.');
        //     return redirect(route('logBooks.index'));
        // }

        $schedules = $user->allSchedules;

        $borrowings = $user->allBorrowings;

        return view('log_books.create')->with('schedules', $schedules)->with('borrowings', $borrowings);
    }

    /**
     * Store a newly created LogBook in storage.
     *
     * @param CreateLogBookRequest $request
     *
     * @return Response
     */
    public function store(CreateLogBookRequest $request)
    {
        $input = $request->all();

        $datetime = Carbon::parse($input['date'] . ' ' . $input['time']);

        if ($input['activity_type'] == 1) {
            $activity = $this->scheduleRepository->findWithoutFail($input['activity_id']);
        } else {
            $activity = $this->borrowingRepository->findWithoutFail($input['activity_id']);
        }

        if (empty($activity)) {
            Flash::error('Activity not found');
            return back()->withInput();
        }

        /** @var User $user */
        $user = Auth::user();

        if ($activity instanceof Schedule) {
            if ($activity->users()->isNotEmpty()) {
                $userable = $activity->users()->find($user->id);

                if (empty($user)) {
                    Flash::error('You are not a participant of this activity');
                    return back()->withInput();
                }
            } elseif ($activity->groups()->isNotEmpty()) {
                $userable = $activity->groups()->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->first();

                if (empty($user)) {
                    Flash::error('You are not a participant of this activity');
                    return back()->withInput();
                }
            } else {
                Flash::error('You are not a participant of this activity');
                return back()->withInput();
            }
        } elseif ($activity instanceof Borrowing) {
            if (empty($activity->userable)) {
                Flash::error('You are not a participant of this activity');
                return back()->withInput();
            }

            $userable = $activity->userable;
        }

        $logBookExists = $this->logBookRepository->where('userable_id', $userable->id)
            ->where('userable_type', get_class($userable))
            ->where('logbookable_id', $activity->id)
            ->where('logbookable_type', get_class($activity))
            ->where('type', $input['type'] == 1 ? 'in' : 'out')
            ->exists();

        if ($logBookExists) {
            Flash::error('Log book already exists');
            return back()->withInput();
        }

        if($input['type'] == 2) {
            $logBookExists = $this->logBookRepository->where('userable_id', $userable->id)
                ->where('userable_type', get_class($userable))
                ->where('logbookable_id', $activity->id)
                ->where('logbookable_type', get_class($activity))
                ->where('type', 'in')
                ->exists();

            if (!$logBookExists) {
                Flash::error('Log book check-in does not exist yet. Please check-in first.');
                return back()->withInput();
            }


            if($activity instanceof Borrowing && $activity->quantity < $request->input('quantity')) {
                Flash::error('You cannot return more than what you borrowed. You can only return ' . $activity->quantity);
                return back()->withInput();
            }

            if($activity instanceof Borrowing) {
                $activity->return_quantity = $request->input('quantity');
                $activity->save();
            }

        }



        $logBook = new LogBook([
            'time' => $datetime,
            'report' => $input['report'],
            'type' => $input['type'] == 1 ? 'in' : 'out'
        ]);

        $logBook->userable()->associate($userable);
        $logBook->logbookable()->associate($activity);

        $logBook->save();

        Flash::success('Log Book saved successfully.');
        return redirect(route('logBooks.index'));
    }

    /**
     * Display the specified LogBook.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $logBook = $this->logBookRepository->findWithoutFail($id);

        if (empty($logBook)) {
            Flash::error('Log Book not found');
            return redirect(route('logBooks.index'));
        }

        return view('log_books.show')->with('logBook', $logBook);
    }

    /**
     * Show the form for editing the specified LogBook.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

        $logBook = $this->logBookRepository->findWithoutFail($id);

        if (empty($logBook)) {
            Flash::error('Log Book not found');
            return redirect(route('logBooks.index'));
        }

        return view('log_books.edit')
            ->with('logBook', $logBook);
    }

    /**
     * Update the specified LogBook in storage.
     *
     * @param  int              $id
     * @param UpdateLogBookRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLogBookRequest $request)
    {
        $logBook = $this->logBookRepository->findWithoutFail($id);

        if (empty($logBook)) {
            Flash::error('Log Book not found');
            return redirect(route('logBooks.index'));
        }

        $input = $request->all();
        $logBook = $this->logBookRepository->update($input, $id);

        Flash::success('Log Book updated successfully.');
        return redirect(route('logBooks.index'));
    }

    /**
     * Remove the specified LogBook from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $logBook = $this->logBookRepository->findWithoutFail($id);

        if (empty($logBook)) {
            Flash::error('Log Book not found');
            return redirect(route('logBooks.index'));
        }

        if ($logBook->logbookable instanceof Borrowing) {
            $borrowing = $logBook->logbookable;
            $borrowing->status = 'approved';
            $borrowing->save();
        }

        $this->logBookRepository->delete($id);

        Flash::success('Log Book deleted successfully.');
        return redirect(route('logBooks.index'));
    }
}
