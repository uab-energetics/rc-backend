<?php

namespace App\Events;

use App\Encoding;
use App\Form;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EncodingCreated {
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }

    /** @var Encoding  */
    public $encoding;
    /** @var Form */
    public $form;

    public function __construct(Encoding $encoding) {
        $this->encoding = $encoding;
        $this->form = Form::findOrFail($encoding->form_id);
    }
}
