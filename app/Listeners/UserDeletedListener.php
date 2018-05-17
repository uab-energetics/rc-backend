<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use App\Services\Projects\ProjectService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserDeletedListener {

    /** @var ProjectService  */
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }


    public function handle(UserDeleted $event) {
        $user = $event->user;
        $this->projectService->handleUserDeleted($user);
    }
}
