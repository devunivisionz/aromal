<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jurisdictions;
use App\States;
use App\User;
use App\Contents;
use App\CType;
use App\Quicklink;
use session;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');


//For getting value from session


//For getting session value in blade file


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


       
        return view('dashboard');    
     
    }    

}
