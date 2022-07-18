<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\ResidentialGate;
use App\Visitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ResidentialGateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.residential-gate.index');
    }

    public function residentialGateList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'longitude',
            3 => 'latitude',
            4 => 'cluster',
            4 => 'phone',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = ResidentialGate::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $residentialGates = ResidentialGate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $residentialGates = ResidentialGate::where('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ResidentialGate::where('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($residentialGates)) {
            $no = $start + 1;
            foreach ($residentialGates as $residentialGate) {
                $nestedData['no'] = $no;
                $nestedData['name'] = $residentialGate->name;
                $nestedData['longitude'] = $residentialGate->longitude;
                $nestedData['latitude'] = $residentialGate->latitude;
                $nestedData['cluster'] = $residentialGate->cluster->name;
                $nestedData['phone'] = $residentialGate->phone;
                $nestedData['action'] = "<a href='" . route("admin.residential-gate.edit", $residentialGate->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" . "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $residentialGate->id . "' data-route='/residential-gate/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
                $data[] = $nestedData;
                $no++;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clusters = Cluster::all();

        return view('pages.admin.master-data.residential-gate.create', compact('clusters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
            'cluster' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $residentialGate = new ResidentialGate();
            $residentialGate->name = $request->name;
            $residentialGate->longitude = $request->longitude;
            $residentialGate->latitude = $request->latitude;
            $residentialGate->cluster_id = $request->cluster;
            $residentialGate->phone = $request->phone;
            $residentialGate->ip_mata = $request->ip_mata;
            $residentialGate->ip_dc_data = $request->ip_dc_data;
            $residentialGate->ip_dc_image = $request->ip_dc_image;
            $residentialGate->ip_mysql = $request->ip_mysql;
            $residentialGate->ip_mongo = $request->ip_mongo;
            $residentialGate->ip_dvr = $request->ip_dvr;
            $residentialGate->port_mata = $request->port_mata;
            $residentialGate->port_dc_data = $request->port_dc_data;
            $residentialGate->port_dc_image = $request->port_dc_image;
            $residentialGate->port_mysql = $request->port_mysql;
            $residentialGate->port_mongo = $request->port_mongo;
            $residentialGate->port_dvr = $request->port_dvr;
            $residentialGate->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.residential-gate.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ResidentialGate $residentialGate
     * @return \Illuminate\Http\Response
     */
    public function edit(ResidentialGate $residentialGate)
    {
        $clusters = Cluster::all();

        return view('pages.admin.master-data.residential-gate.edit', compact("residentialGate", "clusters"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ResidentialGate $residentialGate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResidentialGate $residentialGate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
            'cluster' => 'required'
        ]);



        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $residentialGate->name = $request->name;
            $residentialGate->longitude = $request->longitude;
            $residentialGate->latitude = $request->latitude;
            $residentialGate->cluster_id = $request->cluster;
            $residentialGate->phone = $request->phone;
            $residentialGate->ip_mata = $request->ip_mata;
            $residentialGate->ip_dc_data = $request->ip_dc_data;
            $residentialGate->ip_dc_image = $request->ip_dc_image;
            $residentialGate->ip_mysql = $request->ip_mysql;
            $residentialGate->ip_mongo = $request->ip_mongo;
            $residentialGate->ip_dvr = $request->ip_dvr;
            $residentialGate->port_mata = $request->port_mata;
            $residentialGate->port_dc_data = $request->port_dc_data;
            $residentialGate->port_dc_image = $request->port_dc_image;
            $residentialGate->port_mysql = $request->port_mysql;
            $residentialGate->port_mongo = $request->port_mongo;
            $residentialGate->port_dvr = $request->port_dvr;
            $residentialGate->save();

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.residential-gate.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ResidentialGate $residentialGate
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $residentialGate = ResidentialGate::find($request->id);
            $residentialGate->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($residentialGate, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }

    public function residentialRate(Request $request)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        if ($request->phase == 'daily') {
            $customerCountingCurrentTotal = Visitor::whereDate('created_at', Carbon::today())->count();
            $customerCountingPreviousTotal = Visitor::whereDate('created_at', Carbon::yesterday())->count();
        } elseif ($request->phase == 'weekly') {
            $customerCountingCurrentTotal = Visitor::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $customerCountingPreviousTotal = Visitor::where('created_at', Carbon::now()->startOfWeek()->subWeek())->count();
        } elseif ($request->phase == 'monthly') {
            $customerCountingCurrentTotal = Visitor::whereMonth('created_at', Carbon::now()->month)->count();
            $customerCountingPreviousTotal = Visitor::where('created_at', Carbon::now()->subMonth()->month)->count();
        }

        if ($customerCountingCurrentTotal > $customerCountingPreviousTotal) {
            $status = 'mdi mdi-trending-up mr-1';
            $textColor = 'text-success';
        } elseif ($customerCountingCurrentTotal < $customerCountingPreviousTotal) {
            $status = 'mdi mdi-trending-down mr-1';
            $textColor = 'text-danger';
        } else {
            $status = 'mdi mdi-trending-neutral mr-1';
            $textColor = 'text-info';
        }

        $differenceTotal = number_format($customerCountingCurrentTotal - $customerCountingPreviousTotal, 0, ',', '.');

        $result = [
            'current' => $customerCountingCurrentTotal == null ? 0 : $customerCountingCurrentTotal,
            'difference' => '<p class="mb-0 mt-3 text-muted"><span class="' . $textColor . '" id="total-residential-difference-daily">' . "$differenceTotal" . ' <i
                                    class="' . $status . '" id="status-residential-daily"></i></span> From previous period</p>',
        ];

        return jsend_success($result, 200);
    }
}
