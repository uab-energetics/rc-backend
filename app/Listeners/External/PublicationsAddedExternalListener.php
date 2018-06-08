<?php

namespace App\Listeners\External;

use App\Events\External\PublicationsAddedExternal;
use App\Publication;
use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\Publications\PublicationService;
use Illuminate\Support\Facades\DB;

class PublicationsAddedExternalListener {

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


    public function handle(PublicationsAddedExternal $event) {
        $repo_id = $event->repo_id;
        $external_pubs = $event->publications;
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
        $event->message->ack();
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
