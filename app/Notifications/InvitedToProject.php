<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitedToProject extends Notification
{
    use Queueable;

    public $type = NOTIFICATION_INVITE_TO_PROJECT;

    public $project_id;
    public $payload;


    public function __construct($project_id, $payload) {
        $this->project_id = $project_id;
        $this->payload = $payload;
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable) {
        return [
            'type' => $this->type,
            'payload' => $this->payload
        ];
    }
}
