<?php

namespace App\Events;

use App\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectCreated {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Project  */
    public $project;

    public function __construct(Project $project) {
        $this->project = $project;
    }

    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }
}
