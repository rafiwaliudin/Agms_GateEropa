<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->api = new Api();
        $this->apiData = new ApiData();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexReportVisitorCounting()
    {
        return view('pages.admin.report.index-counting');
    }

    public function indexReportVisitorDownload()
    {
        return view('pages.admin.report.index-download');
    }

    public function reportVisitorCountingList(Request $request)
    {
        $filterDate = $request->filterDate !== null ? Carbon::parse($request->filterDate)->format('d-m-Y') : Carbon::now()->format('d-m-Y');

        $dataApi = $this->apiData->getDataCountingInterested($filterDate);
        $date = $dataApi->date;
        $reports = $dataApi->data;

        $data = array();

        $no = $request->start + 1;

        foreach ($reports as $report) {

            $reportData = array(
                'no' => $no,
                'date' => $date,
                'time' => $report->time,
                'view' => $report->view,
                'onsite' => $report->onsite,
                'male' => $report->male,
                'female' => $report->female,
                'average_age' => $report->averageAge,
                'average_male_age' => $report->averageMaleAge,
                'average_female_age' => $report->averageFemaleAge
            );

            array_push($data, $reportData);
            $no++;
        }

        $results = [
            'draw' => $request->draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];

        return json_encode($results, 200);
    }

    public function reportVisitorDownloadList(Request $request)
    {
        try {
            $reports = Report::all();
        } catch (\Exception $exception) {
            $reports = [];
        }


        $data = array();

        $no = $request->start + 1;

        foreach ($reports as $key => $report) {

            //reindex array obj
            $reportData = array(
                'no' => $no,
                'name' => $report->name,
                'created_at' => Carbon::parse($report->created_at)->format('d-m-Y H:i:s'),
                'action' => "<a target='_blank' href='" . $report->path . "' class='btn btn-primary' style='margin-right:10px;'><i class='fa fa-file-download'></i> Download</a>"
            );

            array_push($data, $reportData);
            $no++;
        }

        $results = [
            'draw' => $request->draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];

        return json_encode($results, 200);
    }

    public function downloadReport(Request $request)
    {
        dd($request->all());
    }
}
