<?php


namespace App\Http\Controllers;


use App\Camera;
use App\CameraType;
use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CctvController
{
    public function __construct()
    {
        $this->apiData = new ApiData();
    }

    public function index($type)
    {
        $cameraType = CameraType::where('name', $type)->first();
        $cameras = Camera::where('camera_type_id', $cameraType->id)->paginate(12);

        return view('pages.admin.cctv.index', compact('cameras'));
    }

    public function detail($type, $id)
    {
        $cameraType = CameraType::where('name', $type)->first();

        $camera = Camera::where('id', $id)->where('camera_type_id', $cameraType->id)->first();

        $data = $this->getDataFaceRecognize($camera->id);

        $images = array();

        if (count(json_decode($data)) > 0) {
            foreach (json_decode($data) as $image) {
                array_push($images, $image);
            }
        } else {
            for ($i = 1; $i < 12; $i++) {
                array_push($images, null);
            }
        }

        if ($type == 'recognize') {
            return view('pages.admin.cctv.detail-recognize', compact('camera', 'images'));
        } else {
            return view('pages.admin.cctv.detail', compact('camera'));
        }
    }

    public function getDataFaceRecognize($cameraId)
    {
        $dataRecognitions = $this->apiData->getDataRecognition($cameraId, 11, 1, "", "");

        $images = array();

        if (count($dataRecognitions->frs) > 0) {
            foreach ($dataRecognitions->frs as $dataRecognition) {
                array_push($images, $dataRecognition->photo);
            }
        } else {
            for ($i = 1; $i < 12; $i++) {
                array_push($images, null);
            }
        }

        while (count($images) < 12) {
            array_push($images, null);
        }

        return json_encode($images);

    }


    public function impression($id)
    {
        $camera = Camera::find($id);
        $impression = $this->apiData->getDataImpression($camera->id);
        try {

            if ($impression) {
                $camera->impression = $impression->impressionTotal;
                $camera->save();

                return jsend_success($camera);
            } else {
                return jsend_fail('failed to get data from data collection server');
            }

        } catch (\Exception $exception) {
            jsend_error($exception);
        }
    }

    public function tally($id)
    {
        $camera = Camera::find($id);
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->addDays(1)->format('d-m-Y');

        try {

            $tally = $this->apiData->getDataCounting($camera->id, -1, -1, null, $startDate, $endDate, 1);

            $dataCounting = array();

            $maxValue = array();

            $realCount = array();

            foreach ($tally->counters as $key => $counter) {
                $compareData = array(
                    'name' => $counter->label,
                    'data' => [$counter->count],
                );

                $dataCounting[$key] = $compareData;
                $maxValue[$counter->label] = $counter->count;

                array_push($realCount, $counter->count);
            }

            $accumulate = array();

            foreach ($maxValue as $max) {
                array_push($accumulate, $max);
            }

            $result = [
                'categories' => [Carbon::now()->format('F')],
                'totalRealCount' => array_sum($realCount),
                'totalAccumulateCount' => '~',
                'data' => $dataCounting
            ];

            return jsend_success($result, 200);

        } catch (\Exception $exception) {
            jsend_error($exception);
        }
    }
}
