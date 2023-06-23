<?php

namespace App\Http\Controllers\Webcore;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
