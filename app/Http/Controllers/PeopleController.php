<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\People;


class PeopleController extends Controller
{

    public function indexPeople(){
        $list_people = People::all();
        // if($request ->ajax()){
        //     return datatables()->of($list_people)->make(true);
        // }

        return view('pages.admin.people.history', compact('list_people'));
    }

    // public function __construct()
    // {
    //     $this -> Middleware ('auth');
    //     $this -> People = new People();
    // }

    public function data(Request $request)
    {
        $orderBy = 'people.id';
        switch($request->input('order.0.column')){
            case "0":
                $orderBy = 'people.id';
                break;
            case "1":
                $orderBy = 'people.image';
                break;
            case "2":
                $orderBy = 'people.license_plate';
                break;
            case "3":
                $orderBy = 'people.occupant_id';
                break;
            case "4":
                $orderBy = 'people.visitor_id';
                break;
            case "5":
                $orderBy = 'people.residential_gate_id';
                break;
            case "6":
                $orderBy = 'people.gate_number';
                break;
            case "7":
                $orderBy = 'people.gate_type';
                break;    

        }
        $data = people::select([
            'people.*'
        ]);
        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderby($orderBy,$request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw'=>$request->input('draw'),
            'recordTotal'=>$recordsTotal,
            'recordFiltered'=>$recordsFiltered,
            'data'=>$data
        ]);
    }
    
}
