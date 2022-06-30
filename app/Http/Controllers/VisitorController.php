<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Http\Controllers\Request\ApiData;
use App\Occupant;
use App\Visitor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facade;

class VisitorController extends Controller
{
    public function __construct()
    {
        $this->apiData = new ApiData();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.visitor.index');
    }

    public function visitorList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'license_plate',
            3 => 'qrcode_image_path',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = Visitor::count();
        $totalFiltered = $totalData;

        if (empty($request->input('search.value'))) {
            $visitors = Visitor::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $visitors = Visitor::where('name', 'LIKE', "%{$search}%")
                ->orWhere('license_plate', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Visitor::where('name', 'LIKE', "%{$search}%")
                ->orWhere('license_plate', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($visitors)) {
            $no = $start + 1;
            foreach ($visitors as $visitor) {
                if ($visitor->position_status == "Outside") {
                    $status =  '<span class="badge badge-warning">Outside</span>';
                } elseif ($visitor->position_status == "Inside") {
                    $status = '<span class="badge badge-success">Inside</span>';
                } else {
                    $status = '<span class="badge badge-info">Undefined</span>';
                }

                $nestedData['no'] = $no;
                $nestedData['name'] = $visitor->name;
                $nestedData['license_plate'] = $visitor->license_plate;
                $nestedData['qrcode_image_path'] = '<a data-fancybox="gallery" href="' . $visitor->qrcode_image_path . '" > <img src="' . $visitor->qrcode_image_path . '" alt="user" class="avatar-xs rounded-circle"></a>';
                $nestedData['qrcode_expiry_date'] = Carbon::parse($visitor->qrcode_expiry_date)->format('d-m-Y h:i:s');
                $nestedData['additional_information'] = $visitor->additional_information;
                $nestedData['position_status'] = $status;
                $nestedData['action'] = "<a href='" . route("admin.visitor.edit", $visitor->id) . "' class='btn btn-primary' style='margin-right:10px;'><i class='menu-icon icon-brush'></i>Edit</a>" . "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $visitor->id . "' data-route='visitor/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
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

        return view('pages.admin.visitor.create', compact('clusters'));
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
            'total' => 'required',
            'name' => 'required',
            'license_plate' => 'unique:visitors,license_plate,NULL,NULL,license_plate,0',
            'cluster' => 'required'
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            if ($request->license_plate) {
                $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            } else {
                $licensePlate = null;
            }

            if ($request->total) {
                for ($x = 1; $x <= $request->total; $x++) {
                    $qrCodeString = generateQRCodeString();

                    Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

                    if ($request->qrcode_expiry_date) {
                        $qrcode_expiry_date = Carbon::parse($request->qrcode_expiry_date);
                    } else {
                        $qrcode_expiry_date = Carbon::now()->addDays(1);
                    }

                    $visitor = new Visitor();
                    if ($request->total > 1) {
                        $visitor->name = $request->name . '-' . $x;
                    } else {
                        $visitor->name = $request->name;
                    }

                    $visitor->license_plate = $licensePlate;
                    $visitor->qrcode_image_path = App::make('url')->to('/') . '/assets/uploads/member/' . $qrCodeString . '.png';
                    $visitor->qrcode_string = $qrCodeString;
                    $visitor->qrcode_expiry_date = $qrcode_expiry_date;
                    $visitor->additional_information = $request->additional_information;
                    $visitor->save();
                    $visitor->clusters()->attach($request->cluster);
                }
            }


            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.visitor.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Visitor $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        $clusters = Cluster::all();

        return view('pages.admin.visitor.edit', compact("visitor", "clusters"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Visitor $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'license_plate' => 'unique:visitors,license_plate,NULL,NULL,license_plate,0' . $visitor->id,
            'cluster' => 'required'
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            if ($request->license_plate) {
                $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            } else {
                $licensePlate = null;
            }

            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

            if ($request->qrcode_expiry_date) {
                $qrcode_expiry_date = Carbon::parse($request->qrcode_expiry_date);
            } else {
                $qrcode_expiry_date = Carbon::now()->addDays(1);
            }

            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = App::make('url')->to('/') . '/assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->qrcode_expiry_date = $qrcode_expiry_date;
            $visitor->additional_information = $request->additional_information;
            $visitor->save();
            $visitor->clusters()->sync($request->cluster);

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.visitor.index'));
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Visitor $visitor
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $visitor = Visitor::find($request->id);
            $visitor->clusters()->detach();
            $visitor->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($visitor, 200);
        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }

    public function visitorRate(Request $request)
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
            'difference' => '<p class="mb-0 mt-3 text-muted"><span class="' . $textColor . '" id="total-visitor-difference-daily">' . "$differenceTotal" . ' <i
                                    class="' . $status . '" id="status-visitor-daily"></i></span> From previous period</p>',
        ];

        return jsend_success($result, 200);
    }

    public function visitorChart()
    {
        $datesThisMonth = [];
        $datesThisMonthPeriod = CarbonPeriod::create(Carbon::now()->firstOfMonth(), Carbon::now()->lastOfMonth());
        foreach ($datesThisMonthPeriod as $key => $date) {
            array_push($datesThisMonth, $date->format('d-m-Y'));
        }

        $countsThisMonth = [];
        foreach ($datesThisMonth as $dateThisMonth) {
            $counting = Visitor::whereDate('created_at', Carbon::parse($dateThisMonth))->count();
            array_push($countsThisMonth, $counting);
        }

        $datesLastMonth = [];
        $datesLastMonthPeriod = CarbonPeriod::create(Carbon::now()->subMonth()->firstOfMonth(), Carbon::now()->subMonth()->lastOfMonth());
        foreach ($datesLastMonthPeriod as $key => $date) {
            array_push($datesLastMonth, $date->format('d-m-Y'));
        }

        $countsLastMonth = [];
        foreach ($datesLastMonth as $dateLastMonth) {
            $counting = Visitor::whereDate('created_at', Carbon::parse($dateLastMonth))->count();
            array_push($countsLastMonth, $counting);
        }

        $dataCurrentMonthTotal = Visitor::whereMonth('created_at', Carbon::now()->month)->count();
        $dataPreviousMonthTotal = Visitor::whereMonth('created_at', now()->subMonth()->month)->count();

        $result = [
            [
                'title' => Carbon::now()->subMonth()->format('F'),
                'categories' => $datesThisMonth,
                'data' => $countsThisMonth,
                'total' => $dataPreviousMonthTotal . ' <span class="mdi mdi-human-greeting mr-1"></span>'
            ],
            [
                'title' => Carbon::now()->format('F'),
                'categories' => $datesLastMonth,
                'data' => $countsLastMonth,
                'total' => $dataCurrentMonthTotal . ' <span class="mdi mdi-human-greeting mr-1"></span>'
            ]
        ];

        return jsend_success($result, 200);
    }
}
