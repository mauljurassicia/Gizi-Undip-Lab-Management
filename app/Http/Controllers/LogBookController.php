<?php

namespace App\Http\Controllers;

use App\DataTables\LogBookDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLogBookRequest;
use App\Http\Requests\UpdateLogBookRequest;
use App\Repositories\LogBookRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class LogBookController extends AppBaseController
{
    /** @var  LogBookRepository */
    private $logBookRepository;

    public function __construct(LogBookRepository $logBookRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:logBook-edit', ['only' => ['edit']]);
        $this->middleware('can:logBook-store', ['only' => ['store']]);
        $this->middleware('can:logBook-show', ['only' => ['show']]);
        $this->middleware('can:logBook-update', ['only' => ['update']]);
        $this->middleware('can:logBook-delete', ['only' => ['delete']]);
        $this->middleware('can:logBook-create', ['only' => ['create']]);
        $this->logBookRepository = $logBookRepo;
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
        return view('log_books.create');
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

        $logBook = $this->logBookRepository->create($input);

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

        $this->logBookRepository->delete($id);

        Flash::success('Log Book deleted successfully.');
        return redirect(route('logBooks.index'));
    }

}
