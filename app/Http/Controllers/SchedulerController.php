<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use App\Scheduler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchedulerController extends Controller
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
        return view('pages.admin.master-data.scheduler.index');
    }

    public function schedulerList(Request $request)
    {
        $schedulers = Scheduler::all();

        $data = array();
        $no = $request->start + 1;
        foreach ($schedulers as $scheduler) {

            if ($scheduler->scheduler_id !== null) {
                $buttonScheduler = "<a href='" . route("admin.scheduler.stop", $scheduler->id) . "' class='btn btn-danger' style='margin-right:10px;'><i class='mdi mdi-timer-off'></i> Stop Scheduler</a>";
            } else {
                $buttonScheduler = "<a href='" . route("admin.scheduler.edit", $scheduler->id) . "' class='btn btn-warning' style='margin-right:10px;'> Edit </a>
                 <a href='" . route("admin.scheduler.start", $scheduler->id) . "' class='btn btn-info' style='margin-right:10px;'><i class='mdi mdi-timer'></i> Start Scheduler</a>" .
                    "<a href='#' type='button' data-target='#verification-modal' data-toggle='modal' data-id='" . $scheduler->id . "' data-route='scheduler/delete' class='btn btn-danger delete-button'><i class='icon-delete'></i>Delete</a>";
            }
            $schedulerData = array(
                'no' => $no,
                'name' => $scheduler->name,
                'email_to' => $scheduler->email_to,
                'email_cc_1' => $scheduler->email_cc_1,
                'email_cc_2' => $scheduler->email_cc_2,
                'email_cc_3' => $scheduler->email_cc_3,
                'email_cc_4' => $scheduler->email_cc_4,
                'email_cc_5' => $scheduler->email_cc_5,
                'schedule_time' => $scheduler->schedule_time,
                'range' => $scheduler->range . ' second',
                'action' => $buttonScheduler
            );

            array_push($data, $schedulerData);
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
    public function create()
    {
        return view('pages.admin.master-data.scheduler.create');
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
            'name' => 'required|min:5',
            'email_to' => 'required|string|email|max:255|',
            'email_cc_1' => 'nullable|string|email|max:255|',
            'email_cc_2' => 'nullable|string|email|max:255|',
            'email_cc_3' => 'nullable|string|email|max:255|',
            'email_cc_4' => 'nullable|string|email|max:255|',
            'email_cc_5' => 'nullable|string|email|max:255|',
            'schedule_time' => 'required',
            'range' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {

            $scheduler = new Scheduler();
            $scheduler->name = $request->name;
            $scheduler->email_to = $request->email_to;
            $scheduler->email_cc_1 = $request->email_cc_1;
            $scheduler->email_cc_2 = $request->email_cc_2;
            $scheduler->email_cc_3 = $request->email_cc_3;
            $scheduler->email_cc_4 = $request->email_cc_4;
            $scheduler->email_cc_5 = $request->email_cc_5;
            $scheduler->schedule_time = $request->schedule_time;
            $scheduler->range = $request->range;
            $scheduler->save();

            //Start Service Scheduler
            try {
                $this->startScheduler($scheduler->id);

                flash('Yey! Your data is created.')->success();

                return redirect(route('admin.scheduler.index'));
            } catch (\Exception $exception) {
                $this->stopScheduler($scheduler->id);
                $scheduler->delete();

                flash('Sorry! Your save data is error.')->error();

                return back();
            }
            //End Service Scheduler

            flash('Yey! Your data is created.')->success();

            return redirect(route('admin.scheduler.index'));

        } catch (\Exception $exception) {
            dd($exception);
            flash('Sorry! Your save data is error.')->error();

            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheduler $scheduler)
    {
        return view('pages.admin.master-data.scheduler.edit', compact('scheduler'));
    }

    public function update(Request $request, Scheduler $scheduler)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email_to' => 'required|string|email|max:255|',
            'email_cc_1' => 'nullable|string|email|max:255|',
            'email_cc_2' => 'nullable|string|email|max:255|',
            'email_cc_3' => 'nullable|string|email|max:255|',
            'email_cc_4' => 'nullable|string|email|max:255|',
            'email_cc_5' => 'nullable|string|email|max:255|',
            'schedule_time' => 'required',
            'range' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Input is Wrong.')->error();

            return back()->withInput()->withErrors($validator);
        }

        try {
            $scheduler->name = $request->name;
            $scheduler->email_to = $request->email_to;
            $scheduler->email_cc_1 = $request->email_cc_1;
            $scheduler->email_cc_2 = $request->email_cc_2;
            $scheduler->email_cc_3 = $request->email_cc_3;
            $scheduler->email_cc_4 = $request->email_cc_4;
            $scheduler->email_cc_5 = $request->email_cc_5;
            $scheduler->schedule_time = $request->schedule_time;
            $scheduler->range = $request->range;
            $scheduler->save();

            //Start Service Scheduler
            try {
                $this->startScheduler($scheduler->id);

                flash('Yey! Your data is created.')->success();

                return redirect(route('admin.scheduler.index'));
            } catch (\Exception $exception) {
                $this->stopScheduler($scheduler->id);
                $scheduler->delete();

                flash('Sorry! Your save data is error.')->error();

                return back();
            }
            //End Service Scheduler

            flash('Yey! Your data is updated.')->success();

            return redirect(route('admin.scheduler.index'));

        } catch (\Exception $exception) {
            dd($exception);
            flash('Sorry! Your save data is error.')->error();

            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {

            $scheduler = Scheduler::find($request->id);
            $scheduler->delete();

            flash('Yey! Your data is deleted.')->success();

            return jsend_success($scheduler, 200);

        } catch (ModelNotFoundException $exception) {
            flash('Sorry! Error on process delete.')->error();

            return jsend_fail('fail to delete data');
        }
    }

    public function startScheduler($id)
    {
        $scheduler = Scheduler::find($id);

        try {
            $startScheduler = $this->apiData->startScheduler($scheduler);

            $data = $startScheduler->info;

            $scheduler->scheduler_id = $data->_id;
            $scheduler->save();

            flash('Yey! Scheduler Started')->success();

            return back();

        } catch (\Exception $exception) {
            $stopScheduler = $this->apiData->stopScheduler($scheduler);
            $scheduler->scheduler_id = null;
            $scheduler->save();

            flash('Sorry! Scheduler cant starting')->error();

            return back();
        }
    }

    public function stopScheduler($id)
    {
        try {
            $scheduler = Scheduler::find($id);

            $stopScheduler = $this->apiData->stopScheduler($scheduler);

            $scheduler->scheduler_id = null;
            $scheduler->save();

            flash('Yey! Scheduler is stopped')->success();

            return back();

        } catch (\Exception $exception) {
            flash('Sorry! Scheduler cant stop')->error();

            return back();
        }
    }
}
