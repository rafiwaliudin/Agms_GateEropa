<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return view('pages.admin.notification.index');
    }

    public function notificationList(Request $request)
    {
        $notifications = auth()->user()->notifications;

        $data = array();
        $no = $request->start + 1;
        foreach ($notifications as $notification) {

            $notificationData = array(
                'no' => $no,
                'action' => $notification->data['action'],
                'message' => $notification->data['message'],
                'location' => $notification->data['location'],
                'camera_status' => $notification->data['cameraStatus'],
                'timestamp' => Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans(),
            );

            array_push($data, $notificationData);
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
}
