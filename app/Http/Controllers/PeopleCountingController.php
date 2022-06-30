<?php

namespace App\Http\Controllers;
use App\PeopleCounting;
use Carbon\Carbon;
use App\Http\Controllers\Request\ApiData;

use Illuminate\Http\Request;

class PeopleCountingController extends Controller
{
    public function __construct()
    {
        $this->apiData = new ApiData();
    }

    public function indexHistory()
    {
        return view('pages.admin.people_counting.history');
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
        $totalData = PeopleCounting::count();
        $totalFiltered = $totalData;

        if (empty($request->input('search.value'))) {
            $histories = PeopleCounting::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $histories = PeopleCounting::where('license_plate', 'LIKE', "%{$search}%")
                ->join('residential_gates', 'residential_gates.id', '=', 'gates.residential_gate_id')
                ->join('clusters', 'clusters.id', '=', 'residential_gates.cluster_id')
                ->join('areas', 'areas.id', '=', 'clusters.area_id')
                ->orWhere('residential_gates.name', 'LIKE', "%{$search}%")
                ->orWhere('clusters.name', 'LIKE', "%{$search}%")
                ->orWhere('areas.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('gates.id', $dir)
                ->get();

            $totalFiltered = PeopleCounting::Where('license_plate', 'LIKE', "%{$search}%")
                ->count();
        }

        if ($startDate and $endDate) {
            $histories = PeopleCounting::whereBetween('created_at', [$startDate, $endDate])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = PeopleCounting::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        $data = array();
        $no = $request->start + 1;
        foreach ($histories as $history) {
            $historyData = array(
                'no' => $no,
                'image' => '<a data-fancybox="gallery" href="' .  $history->image . '"><img src="' . $history->image . '" alt="user" class="avatar-xs rounded-circle"></a>',
                'license_plate' => '<h5 class="font-size-15 mb-0 text-sm-left">' . $history->license_plate . '</h5>',
                'area' => empty($history->residentialGate->cluster->area->name) ? "" : $history->residentialGate->cluster->area->name,
                'cluster' => empty($history->residentialGate->cluster->name) ? "" : $history->residentialGate->cluster->name,
                'residentialGate' => empty($history->residentialGate->name) ? "" : $history->residentialGate->name,
                'status' => empty($history->status) ? "" : $history->status,
                'timestamp' => empty($history->created_at) ? "" : Carbon::parse($history->created_at)->format('d-m-Y h:i:s'),
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
