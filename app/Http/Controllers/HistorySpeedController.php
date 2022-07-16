<?php

namespace App\Http\Controllers;

use App\HistorySpeed;
use Illuminate\Http\Request;
use App\Http\Controllers\Request\ApiData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HistorySpeedController extends Controller
{
    public function __construct()
    {
        $this->apiData = new ApiData();
    }

    public function index()
    {
        return view('pages.admin.speed.history');
    }

    public function historyList(Request $request)
    {
        $startDate = $request->startDate !== null ? Carbon::parse($request->startDate . "00:00:00") : "";
        $endDate = $request->endDate !== null ? Carbon::parse($request->endDate . "24:00:00") : "";

        $columns = array(
            0 => 'id',
            1 => 'speed',
            2 => 'plate_number',
            3 => 'datetime',
            4 => 'image',
            5 => 'create_at',
            6 => 'update_at',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = HistorySpeed::count();
        $totalFiltered = $totalData;

        if (empty($request->input('search.value'))) {
            $histories = HistorySpeed::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            Log::emergency($histories);        
            
        } else {
            $search = $request->input('search.value');

            $histories = HistorySpeed::where('plate_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('gates.id', $dir)
                ->get();

            $totalFiltered = HistorySpeed::Where('plate_number', 'LIKE', "%{$search}%")
                ->count();
        }

        if ($startDate and $endDate) {
            $histories = HistorySpeed::whereBetween('created_at', [$startDate, $endDate])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = HistorySpeed::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        Log::emergency($histories);        
            
        $data = array();
        $no = $request->start + 1;
        foreach ($histories as $history) {
            $historyData = array(
                'no' => $no,
                'image' => '<a data-fancybox="gallery" href="' . "http://13.228.116.97:34568/" . $history->image . '"><img src="' . "http://13.228.116.97:34568/" . $history->image . '" alt="'.$history->plate_number.'" class="avatar-xs"></a>',
                'plate_number' => '<h5 class="font-size-15 mb-0 text-sm-left">' . $history->plate_number . '</h5>',
                'speed' => '<h5 class="font-size-15 mb-0 text-sm-left">' . $history->speed . ' km/h</h5>',
                'timestamp' => empty($history->datetime) ? "" : Carbon::parse($history->datetime)->format('d-m-Y H:i:s'),
            );

            array_push($data, $historyData);
            $no++;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return json_encode($json_data);
    }
}
