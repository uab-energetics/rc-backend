<?php

namespace App\Services\RabbitMQ\Handlers;

use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\RabbitMQ\Core\RabbitMessage;
use App\Services\RabbitMQ\Core\RabbitMessageHandler;
use Illuminate\Support\Facades\DB;

class PubRepoDeleted implements RabbitMessageHandler {

    /** @var ProjectService  */
    protected $projectService;
    /** @var ProjectFormService  */
    protected $projectFormService;

    public function __construct(ProjectService $projectService, ProjectFormService $projectFormService) {
        $this->projectService = $projectService;
        $this->projectFormService = $projectFormService;
    }


    public function handle(RabbitMessage $message) {
        $params = $message->payload();
        $repo_id = $params['repoID'];

        print("$repo_id deleted" . PHP_EOL);

        DB::beginTransaction();

        $this->projectService->handleRepoDeleted($repo_id);
        $this->projectFormService->handleRepoDeleted($repo_id);

        DB::commit();
        $message->acknowledge();
    }
}
