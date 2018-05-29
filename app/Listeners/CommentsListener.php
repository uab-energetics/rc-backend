<?php

namespace App\Listeners;

use App\Events\CommentUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Pusher;

class CommentsListener
{
    private $pusher;

    public function __construct(Pusher $pusher) {
        $this->pusher = $pusher;
    }

    public function handle(CommentUpdate $event) {
        $channel = "comments." . $event->channel->id;
        $event = CommentUpdate::WEB_SOCKET_EVENT;
        $this->pusher->trigger($channel, $event, []);
    }
}
