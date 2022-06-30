<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\QrCodeVehicle;
use App\Vehicle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facade;

class VehicleController extends Controller
{
    public function vehicleList(Request $request)
    {
        $limit = $request->limit;
        $page = $request->page === 1 ? 0 : ($request->page - 1) * $limit;

        $totalData = Vehicle::where('occupant_id', $request->occupant_id)->count();

        $vehicles = Vehicle::where('occupant_id', $request->occupant_id)
            ->offset($page)
            ->limit($limit)
            ->orderBy('id', $request->order)
            ->get();

        $data = array();
        if (!empty($vehicles)) {
            $no = 1;
            foreach ($vehicles as $vehicle) {
                $nestedData['no'] = $no;
                $nestedData['vehicle_id'] = $vehicle->id;
                $nestedData['license_plate'] = $vehicle->license_plate;
                $nestedData['car_type'] = $vehicle->car_type;
                $nestedData['car_color'] = $vehicle->car_color;
                $nestedData['qr_code'] = $vehicle->qrCodeVehicle == null ? "" : $vehicle->qrCodeVehicle->qrcode_image_path;
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
            'license_plate' => 'required|string|unique:vehicles',
            'car_type' => 'required',
            'car_color' => 'required',
        ]);

        if ($validator->fails()) {

            return jsend_fail($validator->errors()->messages(), 400);
        }

        try {

            $vehicle = new Vehicle();
            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->occupant_id = $request->occupant_id;
            $vehicle->save();

            $qrCodeString = generateQRCodeString();
            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/vehicle/' . $qrCodeString . '.png'));
            $qrCodeVehicle = new QrCodeVehicle();
            $qrCodeVehicle->vehicle_id = $vehicle->id;
            $qrCodeVehicle->qrcode_image_path = '/assets/uploads/vehicle/' . $qrCodeString . '.png';
            $qrCodeVehicle->qrcode_string = $qrCodeString;
            $qrCodeVehicle->save();

            return jsend_success($vehicle, 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception);
        }
    }

   /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|unique:vehicles,license_plate,' . $vehicle->id,
            'car_type' => 'required',
            'car_color' => 'required',
        ]);

        if ($validator->fails()) {

            return jsend_fail($validator->errors()->messages(), 400);
        }

        try {

            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->occupant_id = $request->occupant_id;
            $vehicle->save();

            $qrCodeString = generateQRCodeString();
            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/vehicle/' . $qrCodeString . '.png'));
            $qrCodeVehicle = new QrCodeVehicle();
            $qrCodeVehicle->vehicle_id = $vehicle->id;
            $qrCodeVehicle->qrcode_image_path = '/assets/uploads/vehicle/' . $qrCodeString . '.png';
            $qrCodeVehicle->qrcode_string = $qrCodeString;
            $qrCodeVehicle->save();

            return jsend_success($vehicle, 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception);
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
            $vehicle = Vehicle::find($request->vehicle_id);
            if ($vehicle) {
                $vehicle->delete();
            } else {
                return jsend_error('data not found');
            }

            return jsend_success('success data deleted', 200);

        } catch (ModelNotFoundException $exception) {

            return jsend_error($exception->getMessage());
        }
    }
}
