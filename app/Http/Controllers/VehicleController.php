<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\QrCodeVehicle;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facade;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.vehicle.index');
    }

    public function vehicleList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'license_plate',
            2 => 'car_type',
            3 => 'car_color',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = Vehicle::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $vehicles = Vehicle::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $vehicles = Vehicle::where('license_plate', 'LIKE', "%{$search}%")
                ->orWhere('car_type', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Vehicle::where('license_plate', 'LIKE', "%{$search}%")
                ->orWhere('car_type', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($vehicles)) {
            $no = $start + 1;
            foreach ($vehicles as $vehicle) {
                if ($vehicle->position_status == "Outside") {
                    $status =  '<span class="badge badge-warning">Outside</span>';
                } elseif ($vehicle->position_status == "Inside") {
                    $status = '<span class="badge badge-success">Inside</span>';
                } else {
                    $status = '<span class="badge badge-info">Undefined</span>';
                }

                $nestedData['no'] = $no;
                $nestedData['license_plate'] = $vehicle->license_plate;
                $nestedData['car_type'] = $vehicle->car_type;
                $nestedData['car_color'] = $vehicle->car_color;
                $nestedData['release_status'] = $vehicle->release_status == "Lock" ? '<span class="badge badge-warning">Lock</span>' : '<span class="badge badge-success">Unlock</span>';
                $nestedData['time_status'] = $vehicle->time_status == null ? "" : Carbon::parse($vehicle->time_status)->format("d-m-Y H:i:s");
                $nestedData['position_status'] = $status;
                $nestedData['action'] = "<a href='" . route("admin.vehicle.edit", $vehicle->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" . "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $vehicle->id . "' data-route='/vehicle/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
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

        return view('pages.admin.master-data.vehicle.create', compact('clusters'));
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
            'license_plate' => 'required|string|unique:vehicles',
            'car_type' => 'required',
            'car_color' => 'required',
            'cluster' => 'required'
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $vehicle = new Vehicle();
            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->release_status = Carbon::parse($request->time_status) < Carbon::now() ? "Lock" : "Unlock";
            $vehicle->time_status = $request->time_status == null ? Carbon::now()->addHour() : Carbon::parse($request->time_status);
            $vehicle->save();
            $vehicle->clusters()->attach($request->cluster);

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.vehicle.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Vehicle $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        $clusters = Cluster::all();

        return view('pages.admin.master-data.vehicle.edit', compact("vehicle", "clusters"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Vehicle $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|unique:vehicles,license_plate,' . $vehicle->id,
            'car_type' => 'required',
            'car_color' => 'required',
            'cluster' => 'required'
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->release_status = Carbon::parse($request->time_status) < Carbon::now() ? "Lock" : "Unlock";
            $vehicle->time_status = $request->time_status == null ? Carbon::now()->addHour() : Carbon::parse($request->time_status);
            $vehicle->save();
            $vehicle->clusters()->sync($request->cluster);

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.vehicle.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Vehicle $vehicle
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $vehicle = Vehicle::find($request->id);
            $vehicle->clusters()->detach();
            $vehicle->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($vehicle, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
