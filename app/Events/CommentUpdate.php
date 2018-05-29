<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentUpdate {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    CONST WEB_SOCKET_EVENT = 'channel-change';

    public $channel;
    public $comment;

    public function __construct($channel, $comment = null) {
        $this->channel = $channel;
        $this->comment = $comment;
    }

}
