<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facade;

class VisitorController extends Controller
{
    public function visitorList(Request $request)
    {
        $limit = $request->limit;
        $page = $request->page === 1 ? 0 : ($request->page - 1) * $limit;

        $totalData = Visitor::where('occupant_id', $request->occupant_id)->count();

        $visitors = Visitor::where('occupant_id', $request->occupant_id)
            ->offset($page)
            ->limit($limit)
            ->orderBy('id', $request->order)
            ->get();

        $data = array();
        if (!empty($visitors)) {
            $no = 1;
            foreach ($visitors as $visitor) {
                $nestedData['no'] = $no;
                $nestedData['visitor_id'] = $visitor->id;
                $nestedData['name'] = $visitor->name;
                $nestedData['license_plate'] = $visitor->license_plate;
                $nestedData['qr_code'] = $visitor->qrcode_image_path == null ? "" : $visitor->qrcode_image_path;
                $nestedData['qrcode_expiry_date'] = Carbon::parse($visitor->qrcode_expiry_date)->format('d-m-Y h:i:s');
                $nestedData['additional_information'] = $visitor->additional_information;
                $data[] = $nestedData;
                $no++;
            }
        }

        $json_data = array(
            "recordsTotal" => intval($totalData),
            "data" => $data
        );

        return jsend_success($json_data);
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
            'name' => 'required',
            'license_plate' => 'required',
            'occupant_id' => 'required',
        ]);

        if ($validator->fails()) {

            return jsend_fail($validator->errors()->messages(), 400);
        }

        try {

            $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

            if($request->qrcode_expiry_date) {
                $qrcode_expiry_date = Carbon::parse($request->qrcode_expiry_date);
            } else {
                $qrcode_expiry_date = Carbon::now()->addDays(1);
            }

            $visitor = new Visitor();
            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = '/assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->occupant_id = $request->occupant_id;
            $visitor->qrcode_expiry_date = $qrcode_expiry_date;
            $visitor->additional_information = $request->additional_information;
            $visitor->save();

            return jsend_success($visitor, 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception);
        }
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
            'license_plate' => 'required',
            'occupant_id' => 'required',
        ]);

        if ($validator->fails()) {

            return jsend_fail($validator->errors()->messages(), 400);
        }

        try {

            $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));


            if($request->qrcode_expiry_date) {
                $qrcode_expiry_date = Carbon::parse($request->qrcode_expiry_date);
            } else {
                $qrcode_expiry_date = Carbon::now()->addDays(1);
            }

            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = 'assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->occupant_id = $request->occupant_id;
            $visitor->qrcode_expiry_date = $qrcode_expiry_date;
            $visitor->additional_information = $request->additional_information;
            $visitor->save();

            return jsend_success($visitor, 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception);
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
            $visitor = Visitor::find($request->visitor_id);
            if ($visitor) {
                $visitor->delete();
            } else {
                return jsend_error('data not found');
            }

            return jsend_success('success data deleted', 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception->getMessage());
        }
    }
}
