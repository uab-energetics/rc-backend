<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use App\Services\Projects\ProjectService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpdatedListener {

    /** @var ProjectService  */
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }


    public function handle(UserUpdated $event) {
        $user = $event->user;
    }
}
