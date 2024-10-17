<?php

namespace App\Http\Controllers;

use App\DataTables\GuestDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Repositories\UserRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class GuestController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:guest-edit', ['only' => ['edit']]);
        $this->middleware('can:guest-store', ['only' => ['store']]);
        $this->middleware('can:guest-show', ['only' => ['show']]);
        $this->middleware('can:guest-update', ['only' => ['update']]);
        $this->middleware('can:guest-delete', ['only' => ['delete']]);
        $this->middleware('can:guest-create', ['only' => ['create']]);
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Guest.
     *
     * @param GuestDataTable $guestDataTable
     * @return Response
     */
    public function index(GuestDataTable $guestDataTable)
    {
        return $guestDataTable->render('guests.index');
    }

    /**
     * Show the form for creating a new Guest.
     *
     * @return Response
     */
    public function create()
    {
        

        return view('guests.create');
    }

    /**
     * Store a newly created Guest in storage.
     *
     * @param CreateGuestRequest $request
     *
     * @return Response
     */
    public function store(CreateGuestRequest $request)
    {
        $input = $request->all();

        $guest = $this->userRepository->create($input);

        Flash::success('Guest saved successfully.');
        return redirect(route('guests.index'));
    }

    /**
     * Display the specified Guest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $guest = $this->userRepository->findWithoutFail($id);

        if (empty($guest)) {
            Flash::error('Guest not found');
            return redirect(route('guests.index'));
        }

        return view('guests.show')->with('guest', $guest);
    }

    /**
     * Show the form for editing the specified Guest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        

        $guest = $this->userRepository->findWithoutFail($id);

        if (empty($guest)) {
            Flash::error('Guest not found');
            return redirect(route('guests.index'));
        }

        return view('guests.edit')
            ->with('guest', $guest);
    }

    /**
     * Update the specified Guest in storage.
     *
     * @param  int              $id
     * @param UpdateGuestRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGuestRequest $request)
    {
        $guest = $this->userRepository->findWithoutFail($id);

        if (empty($guest)) {
            Flash::error('Guest not found');
            return redirect(route('guests.index'));
        }

        $input = $request->all();
        $guest = $this->userRepository->update($input, $id);

        Flash::success('Guest updated successfully.');
        return redirect(route('guests.index'));
    }

    /**
     * Remove the specified Guest from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $guest = $this->userRepository->findWithoutFail($id);

        if (empty($guest)) {
            Flash::error('Guest not found');
            return redirect(route('guests.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Guest deleted successfully.');
        return redirect(route('guests.index'));
    }

    /**
     * Store data Guest from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $guest = $this->userRepository->create($item->toArray());
            });
        });

        Flash::success('Guest saved successfully.');
        return redirect(route('guests.index'));
    }
}
