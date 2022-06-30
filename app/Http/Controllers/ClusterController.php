<?php

namespace App\Http\Controllers;

use App\Area;
use App\Cluster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.cluster.index');
    }

    public function clusterList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'longitude',
            3 => 'latitude',
            4 => 'area',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = Cluster::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $clusters = Cluster::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $clusters = Cluster::where('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Cluster::where('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($clusters)) {
            $no = $start + 1;
            foreach ($clusters as $cluster) {
                $nestedData['no'] = $no;
                $nestedData['name'] = $cluster->name;
                $nestedData['longitude'] = $cluster->longitude;
                $nestedData['latitude'] = $cluster->latitude;
                $nestedData['area'] = $cluster->area->name;
                $nestedData['action'] = "<a href='" . route("admin.cluster.edit", $cluster->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" . "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $cluster->id . "' data-route='/cluster/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
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
        $areas = Area::all();

        return view('pages.admin.master-data.cluster.create', compact('areas'));
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
            'area' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $cluster = new Cluster();
            $cluster->name = $request->name;
            $cluster->longitude = $request->longitude;
            $cluster->latitude = $request->latitude;
            $cluster->area_id = $request->area;
            $cluster->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.cluster.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Cluster $cluster
     * @return \Illuminate\Http\Response
     */
    public function edit(Cluster $cluster)
    {
        $areas = Area::all();

        return view('pages.admin.master-data.cluster.edit', compact("cluster", "areas"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cluster $cluster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cluster $cluster)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
            'area' => 'required'
        ]);


        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $cluster->name = $request->name;
            $cluster->longitude = $request->longitude;
            $cluster->latitude = $request->latitude;
            $cluster->area_id = $request->area;
            $cluster->save();

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.cluster.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Cluster $cluster
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $cluster = Cluster::find($request->id);
            $cluster->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($cluster, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
