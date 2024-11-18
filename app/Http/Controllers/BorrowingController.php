<?php

namespace App\Http\Controllers;

use App\DataTables\BorrowingDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Repositories\BorrowingRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class BorrowingController extends AppBaseController
{
    /** @var  BorrowingRepository */
    private $borrowingRepository;

    public function __construct(BorrowingRepository $borrowingRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:borrowing-edit', ['only' => ['edit']]);
        $this->middleware('can:borrowing-store', ['only' => ['store']]);
        $this->middleware('can:borrowing-show', ['only' => ['show']]);
        $this->middleware('can:borrowing-update', ['only' => ['update']]);
        $this->middleware('can:borrowing-delete', ['only' => ['delete']]);
        $this->middleware('can:borrowing-create', ['only' => ['create']]);
        $this->borrowingRepository = $borrowingRepo;
    }

    /**
     * Display a listing of the Borrowing.
     *
     * @param BorrowingDataTable $borrowingDataTable
     * @return Response
     */
    public function index()
    {
        return view('borrowings.index');
    }

    /**
     * Show the form for creating a new Borrowing.
     *
     * @return Response
     */
    public function create()
    {
        return view('borrowings.create');
    }

    /**
     * Store a newly created Borrowing in storage.
     *
     * @param CreateBorrowingRequest $request
     *
     * @return Response
     */
    public function store(CreateBorrowingRequest $request)
    {
        $input = $request->all();

        $borrowing = $this->borrowingRepository->create($input);

        Flash::success('Borrowing saved successfully.');
        return redirect(route('borrowings.index'));
    }

    /**
     * Display the specified Borrowing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        return view('borrowings.show')->with('borrowing', $borrowing);
    }

    /**
     * Show the form for editing the specified Borrowing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        

        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        return view('borrowings.edit')
            ->with('borrowing', $borrowing);
    }

    /**
     * Update the specified Borrowing in storage.
     *
     * @param  int              $id
     * @param UpdateBorrowingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBorrowingRequest $request)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        $input = $request->all();
        $borrowing = $this->borrowingRepository->update($input, $id);

        Flash::success('Borrowing updated successfully.');
        return redirect(route('borrowings.index'));
    }

    /**
     * Remove the specified Borrowing from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $borrowing = $this->borrowingRepository->findWithoutFail($id);

        if (empty($borrowing)) {
            Flash::error('Borrowing not found');
            return redirect(route('borrowings.index'));
        }

        $this->borrowingRepository->delete($id);

        Flash::success('Borrowing deleted successfully.');
        return redirect(route('borrowings.index'));
    }

    /**
     * Store data Borrowing from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $borrowing = $this->borrowingRepository->create($item->toArray());
            });
        });

        Flash::success('Borrowing saved successfully.');
        return redirect(route('borrowings.index'));
    }
}
