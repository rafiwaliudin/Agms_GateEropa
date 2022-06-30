<?php

namespace App\Http\Controllers;

use App\QrCodeVehicle;
use App\Vehicle;
use App\Visitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facade;

class QrController extends Controller
{
    public function gate()
    {
        return view('pages.post-qr.pages.gate');
    }

    public function qr($id)
    {
        if ($id == 0) {
            $qrCode = "assets/images/qr-code.png";
            $licensePlate = null;
        } else {
            $visitor = Visitor::where('id', $id)->first();
            $qrCode = $visitor->qrcode_image_path;
            $licensePlate = $visitor->license_plate;
        }

        return view('pages.post-qr.pages.qr', compact('qrCode', 'licensePlate'));
    }

    public function form()
    {
        return view('pages.post-qr.pages.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'license_plate' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

            $visitor = new Visitor();
            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = 'assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('app.qr', $visitor->id));

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    public function openGate(Request $request)
    {
        try {
            $this->apiData->openGate($request->id);

            $message = "Gate " . $request->id . " Opened";

            return jsend_success($message, 200);
        } catch (\Exception $exception) {
            $message = "Gate " . $request->id . " Can't Opened";

            return jsend_success($message, 200);
        }

    }

    public function index()
    {
        $user = Auth::user();

        $occupant = $user->occupant;

        $vehicles = Vehicle::where('occupant_id', $occupant->id)->paginate();

        return view('pages.app-agms.vehicle.index', compact('vehicles'));
    }


    public function Vehicle()
    {
        $user = Auth::user();

        $occupant = $user->occupant;

        $vehicles = Vehicle::where('occupant_id', $occupant->id)->paginate();

        return view('pages.app-agms.vehicle.list', compact('vehicles'));
    }

    public function VehicleCreate()
    {
        return view('pages.app-agms.vehicle.create');
    }

    public function storeVehicle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|string|unique:vehicles',
            'car_type' => 'required',
            'car_color' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $user = Auth::user();

            $occupant = $user->occupant;

            $vehicle = new Vehicle();
            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->occupant_id = $occupant->id;
            $vehicle->save();

            $qrCodeString = generateQRCodeString();
            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/vehicle/' . $qrCodeString . '.png'));
            $qrCodeVehicle = new QrCodeVehicle();
            $qrCodeVehicle->vehicle_id = $vehicle->id;
            $qrCodeVehicle->qrcode_image_path = '/assets/uploads/vehicle/' . $qrCodeString . '.png';
            $qrCodeVehicle->qrcode_string = $qrCodeString;
            $qrCodeVehicle->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('app.list'));

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    public function VehicleEdit(Vehicle $vehicle)
    {
        return view('pages.app-agms.vehicle.edit', compact('vehicle'));
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|unique:vehicles,license_plate,' . $vehicle->id,
            'car_type' => 'required',
            'car_color' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $vehicle->license_plate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $vehicle->car_type = $request->car_type;
            $vehicle->car_color = $request->car_color;
            $vehicle->save();

            flash('Yey! Your data is updated.')->success();

            return redirect(route('app.list'));

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    public function deleteVehicle(Request $request)
    {
        try {
            $vehicle = Vehicle::find($request->id);
            $vehicle->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($vehicle, 200);

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }

    public function Visitor()
    {
        $user = Auth::user();

        $occupant = $user->occupant;

        $visitors = Visitor::where('occupant_id', $occupant->id)->paginate();

        return view('pages.app-agms.visitor.index', compact('visitors'));
    }

    public function VisitorCreate()
    {
        return view('pages.app-agms.visitor.create');
    }

    public function storeVisitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'license_plate' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

            $visitor = new Visitor();
            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = '/assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->occupant_id = Auth::user()->occupant->id;
            $visitor->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('app.visitor'));

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    public function VisitorEdit(Visitor $visitor)
    {
        return view('pages.app-agms.visitor.edit', compact('visitor'));
    }

    public function updateVisitor(Request $request, Visitor $visitor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'license_plate' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $licensePlate = strtoupper(preg_replace('/\s+/', '', $request->license_plate));
            $qrCodeString = generateQRCodeString();

            Facade::size(1000)->format('png')->generate($qrCodeString, public_path('assets/uploads/member/' . $qrCodeString . '.png'));

            $visitor->name = $request->name;
            $visitor->license_plate = $licensePlate;
            $visitor->qrcode_image_path = '/assets/uploads/member/' . $qrCodeString . '.png';
            $visitor->qrcode_string = $qrCodeString;
            $visitor->occupant_id = Auth::user()->occupant->id;
            $visitor->save();

            flash('Yey! Your data is created.')->success();

            return redirect(route('app.visitor'));

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process saving.')->error();

            return back();
        }
    }

    public function deleteVisitor(Request $request)
    {
        try {
            $visitor = Visitor::find($request->id);
            $visitor->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($visitor, 200);

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }

    public function VisitorAdd()
    {
        return view('pages.app-agms.visitor.create');
    }


}
