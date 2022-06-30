<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Camera;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $cameras = Camera::where('camera_type_id', 1)->get();

        return view('pages.admin.attendance.index', compact('cameras'));
    }

    public function attendanceList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'photo',
            2 => 'name',
            3 => 'date',
            4 => 'gender',
            5 => 'temperature',
            6 => 'emotion',
            7 => 'age',
            8 => 'wear_mask',
            9 => 'first_detected',
            10 => 'last_detected'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = Attendance::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $attendances = Attendance::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $attendances = Attendance::where('name', 'LIKE', "%{$search}%")
                ->orWhere('date', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Attendance::where('name', 'LIKE', "%{$search}%")
                ->orWhere('date', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($attendances)) {
            $no = $start + 1;
            foreach ($attendances as $attendance) {
                $nestedData['no'] = $no;
                $nestedData['photo'] = '<a data-fancybox="gallery" href="' . "http://" . env('IP_API_DATA_COLLECTOR') . ":" . env('IMAGE_PORT') . $attendance->photo . '"><img src="' . "http://" . env('IP_API_DATA_COLLECTOR') . ":" . env('IMAGE_PORT') . $attendance->photo . '" alt="user" class="avatar-xs rounded-circle"></a>';
                $nestedData['name'] = $attendance->employee->name;
                $nestedData['location'] = $attendance->location->name;
                $nestedData['temperature'] = $attendance->temperature;
                $nestedData['date'] = Carbon::parse($attendance->created_at)->format('d-m-Y');
                $nestedData['first_detected'] = $attendance->first_detected;
                $nestedData['last_detected'] = $attendance->last_detected;
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
}
