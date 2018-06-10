<?php

namespace App\Services\RabbitMQ\Handlers;

use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\Publications\PublicationService;
use App\Services\RabbitMQ\Core\RabbitMessage;
use App\Services\RabbitMQ\Core\RabbitMessageHandler;
use Illuminate\Support\Facades\DB;

class ExternalPublicationsAdded implements RabbitMessageHandler {

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
        $external_pubs = $params['publications'];

        DB::beginTransaction();

        $publications = [];
        foreach ($external_pubs as $external_pub) {
            [$params, $external_id] = $this->transformExternalPub($external_pub);
            $existing = $this->publicationService->retrieveByUuid($params['uuid']);
            if ($existing === null) {
                $existing = $this->publicationService->makePublication($params);
            }
            $publications[] = $existing;
            $this->publicationService->addExternalID($existing, $external_id);

        }
        $this->projectService->addPublicationsByRepoId($repo_id, $publications);
        $this->projectFormService->addPublicationsByRepoId($repo_id, $publications);

        DB::commit();
        $message->acknowledge();
    }

    private function transformExternalPub($external) {
        $id = $external['id'];
        $internal = [
            'uuid' => $external['uuid'],
            'source_id' => $external['sourceID'],
            'embedding_url' => $external['embeddingURL'],
            'name' => $external['title'],
        ];
        return [$internal, $id];
    }
}
