<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.area.index');
    }

    public function areaList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'longitude',
            3 => 'latitude',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = Area::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $areas = Area::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $areas = Area::where('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Area::where('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($areas)) {
            $no = $start + 1;
            foreach ($areas as $area) {
                $nestedData['no'] = $no;
                $nestedData['name'] = $area->name;
                $nestedData['longitude'] = $area->longitude;
                $nestedData['latitude'] = $area->latitude;
                $nestedData['action'] = "<a href='" . route("admin.area.edit", $area->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" . "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $area->id . "' data-route='/area/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
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
        return view('pages.admin.master-data.area.create');
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
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $area = new Area();
            $area->name = $request->name;
            $area->longitude = $request->longitude;
            $area->latitude = $request->latitude;
            $area->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.area.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Area $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('pages.admin.master-data.area.edit', compact("area"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Area $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);


        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $area->name = $request->name;
            $area->longitude = $request->longitude;
            $area->latitude = $request->latitude;
            $area->save();

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.area.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Area $area
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $area = Area::find($request->id);
            $area->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($area, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
