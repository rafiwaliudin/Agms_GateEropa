<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNotification extends Notification
{
    use Queueable;

    public $action;

    public $message;

    public $location;

    public $cameraStatus;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action, $message, $location, $cameraStatus, $data)
    {
        $this->action = $action;
        $this->message = $message;
        $this->location = $location;
        $this->cameraStatus = $cameraStatus;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'action' => $this->action,
            'message' => $this->message,
            'location' => $this->location,
            'cameraStatus' => $this->cameraStatus,
            'id' => $this->data->id,
        ];
    }
}
