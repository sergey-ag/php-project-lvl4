<?php

namespace Craftworks\TaskManager\Http\Controllers;

use Illuminate\Http\Request;
use Craftworks\TaskManager\Task;

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
        $myTasks = auth()->user()->tasks;
        return view('home', ['myTasks' => $myTasks]);
    }
}
