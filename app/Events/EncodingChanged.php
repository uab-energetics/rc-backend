<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EncodingChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $encoding_id;

    public function __construct($encoding_id) {
        $this->encoding_id = $encoding_id;
    }

}
