<?php

namespace App\Services\RabbitMQ\Handlers;

use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\Publications\PublicationService;
use App\Services\RabbitMQ\Core\RabbitMessage;
use App\Services\RabbitMQ\Core\RabbitMessageHandler;
use Illuminate\Support\Facades\DB;

class ExternalPublicationsRemoved implements RabbitMessageHandler {

    /** @var PublicationService  */
    protected $publicationService;
    /** @var ProjectService  */
    protected $projectService;
    /** @var ProjectFormService  */
    protected $projectFormService;

    public function __construct(PublicationService $publicationService,
                                ProjectService $projectService,
                                ProjectFormService $projectFormService)
    {
        $this->publicationService = $publicationService;
        $this->projectService = $projectService;
        $this->projectFormService = $projectFormService;
    }


    public function handle(RabbitMessage $message) {
        $params = $message->payload();
        $repo_id = $params['repoID'];
        $external_ids = array_map(function ($id) {
            return strval($id);
        }, $params['publicationIDs']);

        DB::beginTransaction();

        $idMaps = $this->publicationService->retrieveExternalIds($external_ids);
        $publication_ids = $idMaps->get()->pluck('publication_id');
        $idMaps->delete();
        $this->projectService->removePublicationsByRepoId($repo_id, $publication_ids);
        $this->projectFormService->removePublicationsByRepoId($repo_id, $publication_ids);

        DB::commit();
        $message->acknowledge();
    }
}
