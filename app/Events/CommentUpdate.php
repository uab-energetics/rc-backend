<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    CONST WEB_SOCKET_EVENT = 'channel-change';

    public $channel;
    public $comment;

    public function __construct($channel, $comment = null) {
        $this->channel = $channel;
        $this->comment = $comment;
    }

}
