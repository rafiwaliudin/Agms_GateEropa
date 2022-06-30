<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CameraManagement;
use App\Http\Controllers\Request\ApiData;
use Illuminate\Support\Facades\Log;

class CameraManagementController extends Controller
{

    public function __construct()
    {
        $this->apiData = new ApiData();
    }  

    public function index(){

        $cameras = CameraManagement::all();

        Log::emergency($cameras);        
        
        return view('pages.admin.camera-management.index', compact('cameras'));

    }
}
