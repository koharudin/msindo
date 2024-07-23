<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sales;
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
        $sales = Sales::all()->count();
        $revenue = Sales::sum('total');
        $users = User::all()->count();
        return view('home', compact('sales', 'revenue', 'users'));
    }
}
