<?php

namespace App\Http\Controllers;

use App\HistoryGate;
use App\Http\Controllers\Request\ApiData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log; // For data log
// HistoryGate -> ELoquent -> Model
//
class GateController extends Controller
{
    public function __construct()
    {
        $this->apiData = new ApiData();
    }

    public function indexHistory()
    {
        return view('pages.admin.gate.history');
    }

    public function historyList(Request $request)
    {
        $startDate = $request->startDate !== null ? Carbon::parse($request->startDate . "00:00:00") : "";
        $endDate = $request->endDate !== null ? Carbon::parse($request->endDate . "24:00:00") : "";

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'image',
            3 => 'license_plate',
            4 => 'area',
            5 => 'cluster',
            6 => 'residentialGate',
            7 => 'status',
            8 => 'timestamp',
        );
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = HistoryGate::count();
        $totalFiltered = $totalData;
        
        if (empty($request->input('search.value'))) {
            $histories = HistoryGate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            Log::info('search.value');
        } else {
            Log::info('else');
            $search = $request->input('search.value');

            $histories = HistoryGate::where('license_plate', 'LIKE', "%{$search}%")
                //->join('residential_gates', 'history_gates.id', '=', 'residential_gates.history_gate_id')
                //->join('clusters', 'clusters.id', '=', 'history_gates.cluster_id')
                //->join('areas', 'areas.id', '=', 'clusters.area_id')
               // ->orWhere('history_gates.name', 'LIKE', "%{$search}%")
                //->orWhere('clusters.name', 'LIKE', "%{$search}%")
                //->orWhere('areas.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('history_gates.id', $dir)
                ->get();

            $totalFiltered = HistoryGate::Where('license_plate', 'LIKE', "%{$search}%")
                ->count();
        }

        if ($startDate and $endDate) {
            Log::info('startend');
            $histories = HistoryGate::whereBetween('created_at', [$startDate, $endDate])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = HistoryGate::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        $data = array();
        $no = $request->start + 1;
        foreach ($histories as $history) {
            $historyData = array(
                'no' => $no,
                //'image' => '<a data-fancybox="gallery" href="' .$pathx.  $history->image . '"><img src="' .$pathx. $history->image . '" alt=" Image" class="avatar-xs"></a>',
                'image' => '<a data-fancybox="gallery" href="' . "http://" . env('IP_API_DATA_COLLECTOR') . ":"  . env('IMAGE_PORT') . "/" . $history->image . '"><img src="' . "http://" . env('IP_API_DATA_COLLECTOR') . ":" . env('IMAGE_PORT') . "/" . $history->image . '" alt="'.$history->license_plate.'" class="avatar-xs"></a>',
                'license_plate' => '<h5 class="font-size-15 mb-0 text-sm-left">' . $history->license_plate . '</h5>',
                'area' => empty($history->residentialGate->cluster->area->name) ? "" : $history->residentialGate->cluster->area->name,
                'cluster' => empty($history->residentialGate->cluster->name) ? "" : $history->residentialGate->cluster->name,
                'residentialGate' => empty($history->residentialGate->name) ? "" : $history->residentialGate->name,
                'status' => empty($history->status) ? "" : $history->status,
                'timestamp' => empty($history->created_at) ? "" : Carbon::parse($history->created_at)->addHour(7)->format('d-m-Y H:i:s'),
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
        // Log::info($json_data);
        return json_encode($json_data);
    }
}
