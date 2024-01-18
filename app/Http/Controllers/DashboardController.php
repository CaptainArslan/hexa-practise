<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * 
     * Display a view of the user profile
     *
     * @return void
     */
    public function profile()
    {
        return view('user.profile');
    }

}
