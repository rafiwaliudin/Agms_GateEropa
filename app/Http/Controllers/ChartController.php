<?php

namespace App\Http\Controllers;

use App\Chart;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function __construct()
    {
        $this -> Middleware ('auth');
        $this -> Chart = new Chart();
    }

    public function indexLine(){
        $data = [
            'title' => 'Chart View',
            'chart' => $this->Chart->AllData(),
        ];
        return view('pages.admin.chart.history' , $data);
    }
}
