<?php

namespace App\Http\Controllers;

use App\Camera;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
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
        $cameras = Camera::all();

        Log::emergency($cameras);        
        
        return view('pages.admin.dashboard', compact('cameras'));
    }
}
