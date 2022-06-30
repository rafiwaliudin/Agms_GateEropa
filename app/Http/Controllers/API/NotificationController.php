<?php

namespace App\Http\Controllers\API;

use App\Events\NotificationWasCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function add(Request $request)
    {
        try {
            event(new NotificationWasCreated($request->action, $request->message, $request->location, $request->camera_status, \App\User::find(1)));

            $value = array(
                "action" => $request->action,
                "message" => $request->message,
                "location" => $request->location,
                "camera_status" => $request->camera_status,
                "created_by" => \App\User::find(1)
            );
            return jsend_success($value, 200);
        } catch (\Exception $exception) {
            dd($exception);
            return jsend_fail('error parameter');
        }
    }

    public function list(Request $request)
    {
        try {
            return jsend_success(Notification::jsonPaginate());
        } catch (\Exception $exception) {

            return jsend_error($exception);
        }
    }

    public function detail(Request $request)
    {
        try {

            if ($request->id) {

                return jsend_success(Notification::where('id', $request->id)->first());
            } else {

                return jsend_fail('please write a shift id');
            }
        } catch (\Exception $exception) {

            return jsend_error($exception);
        }
    }
}
