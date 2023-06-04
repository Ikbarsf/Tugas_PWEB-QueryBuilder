<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $roleUser = \Auth::user()->roles->pluck('name')[0];
        if ($roleUser == 'Admin') {
            return redirect('/back-admin/dashboard');
        } elseif ($roleUser == 'Mentor') {
            return redirect('/back-mentor/dashboard');
        } elseif ($roleUser == 'Employee') {
            return redirect('/back-employee/dashboard');
        } else {
            return redirect('/logout');
        }
    }
}
