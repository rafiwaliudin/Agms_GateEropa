<?php

namespace App\Http\Controllers;

use App\Camera;
use App\CameraType;
use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use App\ObjectVehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class CameraController extends Controller
{
    public function __construct()
    {
        $this->apiData = new ApiData();
        $this->api = new Api();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.master-data.camera.index');
    }

    public function cameraList(Request $request)
    {
        $cameras = Camera::orderBy('prefix_port', 'desc')->get();

        $data = array();
        $no = $request->start + 1;
        foreach ($cameras as $camera) {

            if ($camera->id_proc !== null) {
                $buttonCameraStatus = "";
            } else {
                $buttonCameraStatus = "<a href='" . route("admin.camera.edit", $camera->id) . "' class='btn btn-warning' style='margin-right:10px;'> Edit </a>" .
                    "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $camera->id . "' data-route='camera/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
            }

            $cameraData = array(
                'no' => $no,
                'name' => $camera->name,
                'link_camera' => $camera->input_link,
                'prefix_port' => $camera->prefix_port,
                'thumbnail' => $camera->thumbnail !== null ? "<a data-fancybox='gallery' href='" . asset('assets/uploads/camera/' . $camera->thumbnail) . "'> <img src='" . asset('assets/uploads/camera/' . $camera->thumbnail) . "' width='60px' height='50px' > </a>" : "<a data-fancybox='gallery' href='" . asset('assets/images/camera-thumbnail.jpg') . "'> <img src='" . asset('assets/images/camera-thumbnail.jpg') . "' width='60px' height='50px' > </a>",
                'action' => $buttonCameraStatus,
            );

            array_push($data, $cameraData);
            $no++;
        }

        $results = [
            'draw' => $request->draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];

        return jsend_success($results, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $objectVehicles = ObjectVehicle::all();

        return view('pages.admin.master-data.camera.create', compact('type', 'objectVehicles'));
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
            'camera_type' => 'required',
            'name' => 'required|min:5',
            'camera_link' => 'required',
            'address' => 'required',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'object' => $request->camera_type !== 'recognize' ? 'required' : 'nullable',
            'intruder_start' => $request->intruder_start == 'masking' ? 'required' : 'nullable',
            'intruder_end' => $request->intruder_end == 'masking' ? 'required' : 'nullable',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $prefixport = Camera::whereNotNull('prefix_port')->orderBy('prefix_port', 'desc')->pluck('prefix_port')->first();

            if (empty($prefixport)) {
                $prefixport = 849;
            }
            $cameraType = CameraType::where('name', $request->camera_type)->first();

            $camera = new Camera();
            $camera->name = $request->name;
            $camera->input_link = $request->camera_link;
            $camera->prefix_port = $prefixport + 1;
            $camera->longitude = $request->longitude;
            $camera->latitude = $request->latitude;
            $camera->camera_type_id = $cameraType->id;
            $camera->address = $request->address;

            if ($request->camera_type == 'recognize') {
                $camera->type_proc = 30;
            } elseif ($request->camera_type == 'masking') {
                $camera->target_objects = implode(",", $request->object);
                $camera->type_proc = 31;
                $camera->intruder_time_start = Carbon::parse($request->intruder_start)->format('h:i:s');
                $camera->intruder_time_end = Carbon::parse($request->intruder_end)->format('h:i:s');
            } elseif ($request->camera_type == 'counting') {
                //default counting lines
                $camera->counting_lines = '0,0,0,0';
                $camera->target_objects = implode(",", $request->object);
                $camera->type_proc = 31;
            }

            //upload file and resize
            if ($request->thumbnail !== null) {
                $image = $request->thumbnail;
                $filename = $image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(800, 533);
                $image_resize->save(public_path('assets/uploads/camera/' . $filename));
                $camera->thumbnail = $filename;
            }

            //end upload file

            $camera->save();

            //Start Camera
            try {
                $this->start($camera->id);

                return redirect(route('admin.camera.preview', $camera->id));
            } catch (\Exception $exception) {

                $this->stop($camera->id);
                $camera->delete();

                return back();
            }

        } catch (\Exception $exception) {

            flash('Sorry! Your save data is error.')->error();

            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Camera $camera)
    {
        $objectVehicles = ObjectVehicle::all();

        return view('pages.admin.master-data.camera.edit', compact('camera', 'objectVehicles'));
    }

    public function upgrade(Request $request, Camera $camera)
    {
        $validator = Validator::make($request->all(), [
            'camera_type' => 'required',
            'name' => 'required|min:5',
            'camera_link' => 'required',
            'address' => 'required',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $prefixport = Camera::whereNotNull('prefix_port')->orderBy('prefix_port', 'desc')->pluck('prefix_port')->first();

            if (empty($prefixport)) {
                $prefixport = 849;
            }
            $cameraType = CameraType::where('name', $request->camera_type)->first();

            $camera->name = $request->name;
            $camera->input_link = $request->camera_link;
            $camera->prefix_port = $prefixport + 1;
            $camera->longitude = $request->longitude;
            $camera->latitude = $request->latitude;
            $camera->camera_type_id = $cameraType->id;
            $camera->address = $request->address;

            if ($request->camera_type == 'recognize') {
                $camera->type_proc = 30;
            } elseif ($request->camera_type == 'masking') {
                $camera->target_objects = implode(",", $request->object);
                $camera->type_proc = 31;
                $camera->intruder_time_start = Carbon::parse($request->intruder_start)->format('h:i:s');
                $camera->intruder_time_end = Carbon::parse($request->intruder_end)->format('h:i:s');
            } elseif ($request->camera_type == 'counting') {
                //default counting lines
                $camera->counting_lines = '0,0,0,0';
                $camera->target_objects = implode(",", $request->object);
                $camera->type_proc = 31;
            }

            //upload file and resize
            if ($request->thumbnail !== null) {
                $image = $request->thumbnail;
                $filename = $image->getClientOriginalName();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(800, 533);
                $image_resize->save(public_path('assets/uploads/camera/' . $filename));
                $camera->thumbnail = $filename;
            }
            //end upload file

            $camera->save();

            //Start Camera
            try {

                $this->start($camera->id);

                return redirect(route('admin.camera.preview', $camera->id));
            } catch (\Exception $exception) {

                $this->stop($camera->id);

                return back();
            }

        } catch (\Exception $exception) {

            flash('Sorry! Your save data is error.')->error();

            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function update(Request $request, Camera $camera)
    {
        $coordinate = array($request->x1, $request->y1, $request->x2, $request->y2);

        $camera->counting_lines = implode(",", $coordinate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $camera = Camera::where('id', $id)->with('cameraType')->first();

        if ($camera->cameraType->id == 1) {
            return view('pages.admin.master-data.camera.preview-recognize', compact('camera'));
        } elseif ($camera->cameraType->id == 2) {
            return view('pages.admin.master-data.camera.preview-counting', compact('camera'));
        } elseif ($camera->cameraType->id == 3) {
            return view('pages.admin.master-data.camera.preview-masking', compact('camera'));
        }
    }

    public function start($id)
    {
        $camera = Camera::find($id);

        try {
            $startCamera = $this->api->startCamera($camera->id);
            $startDataCollection = $this->apiData->startDataCollection($camera->id, 1);

            $result = $startCamera->success;
            $camera->id_proc = $result->id_proc;
            $camera->save();

            flash('Yey! Camera Started')->success();

            return back();

        } catch (\Exception $exception) {
            $stopDataCamera = $this->apiData->stopDataCollection($camera->id);
            $stopCamera = $this->api->stopCamera($camera->id_proc);
            $camera->id_proc = null;
            $camera->save();

            flash('Sorry! Mata cant start camera')->error();

            return back();
        }
    }

    public function stop($id)
    {
        if ($id == 'all') {
            try {

                $cameras = Camera::all();

                foreach ($cameras as $camera) {
                    $camera->id_proc = null;
                    $camera->save();
                }

                $stopAllDataCollection = $this->apiData->stopAllDataCollection();

                $stopAllCamera = $this->api->stopAllCamera();

                flash('Yey! All Camera is stopped')->success();

                return jsend_success('Yey! All Camera is stopped', 200);

            } catch (\Exception $exception) {
                flash('Sorry! Mata and Data Collector server is down')->error();

                return jsend_error('Mata and Data Collector server is down');
            }
        } else {
            try {
                $camera = Camera::find($id);

                $stopCamera = $this->api->stopCamera($camera->id_proc);

                $stopDataCollection = $this->apiData->stopDataCollection($camera->id);

                $camera->id_proc = null;
                $camera->save();

                flash('Yey! Camera is stopped')->success();

                return back();

            } catch (\Exception $exception) {
                flash('Sorry! Mata and Data Collector server is down')->error();

                return back();
            }
        }
    }

    public function screenshot(Request $request)
    {
        try {
            $screenshot = uploadDecode64($request->screenshot, 'camera/screenshot/');

            $camera = Camera::where('id', $request->cameraId)->first();
            $camera->screenshot = $screenshot;
            $camera->save();

            return jsend_success($screenshot, 200);

        } catch (\Exception $exception) {

            return jsend_error($exception);
        }
    }

    public function updateLine(Request $request)
    {
        try {
            $camera = Camera::where('id', $request->cameraId)->first();
            $camera->n_counting_lines = count($request->coordinate);
            $camera->counting_lines = implode('"', $request->coordinate);
            $camera->save();

            $this->stop($camera->id);
            $this->start($camera->id);

            return jsend_success($camera, 200);

        } catch (\Exception $exception) {

            return jsend_error($exception);
        }
    }

    public function updateMask(Request $request)
    {
        try {
            $maskingThumbnail = uploadDecode64($request->maskingThumbnail, 'camera/masking/');

            $camera = Camera::where('id', $request->cameraId)->first();
            $camera->intruder_mask = $maskingThumbnail;
            $camera->save();

            $this->stop($camera->id);
            $this->start($camera->id);

            return jsend_success($camera, 200);

        } catch (\Exception $exception) {

            return jsend_error($exception);
        }
    }

    public function delete(Request $request)
    {
        try {

            $camera = Camera::find($request->id);
            $this->stop($camera->id);
            $camera->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($camera, 200);

        } catch (\Exception $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }
}
