<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\Projects\ProjectService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedListener {

    /** @var ProjectService  */
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }


    public function handle(UserCreated $event) {
        $user = $event->user;
    }
}
