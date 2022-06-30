<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function openGate(Request $request)
    {
       if($request->gateNumber) {
        return jsend_success('gate open',200);
       }

    }
}
