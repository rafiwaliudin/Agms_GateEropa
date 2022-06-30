<?php

namespace App\Listeners;

use App\Events\NotificationWasCreated;
use App\Notifications\NewNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(NotificationWasCreated $event)
    {
        $data = $event->data;

        Notification::send(User::find(1), new NewNotification($event->action, $event->message, $event->location,  $event->cameraStatus, $data));

        $value = array(
            "action" => $event->action,
            "message" => $event->message,
            "location" => $event->location,
            "camera_status" => $event->cameraStatus,
            "created_by" => $data
        );

        return jsend_success($value, 200);
    }
}
