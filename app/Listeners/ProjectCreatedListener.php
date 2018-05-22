<?php

namespace App\Listeners;

use App\Services\Projects\ProjectService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectCreatedListener {

    /** @var ProjectService  */
    public $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event) {
        // publish to rabbitmq
    }
}
