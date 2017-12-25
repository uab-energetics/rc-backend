<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitedToProject extends Mailable
{
    use Queueable, SerializesModels;


    public $callback;
    public $user;
    public $project;

    public function __construct($data)
    {
        $this->callback = $data['callback'];
        $this->user = $data['user'];
        $this->project = $data['project'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invited_to_project');
    }
}
