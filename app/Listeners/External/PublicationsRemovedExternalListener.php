<?php

namespace App\Listeners\External;

use App\Events\External\PublicationsAddedExternal;
use App\Events\External\PublicationsRemovedExternal;
use App\Publication;
use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\Publications\PublicationService;
use Illuminate\Support\Facades\DB;

class PublicationsRemovedExternalListener {

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


    public function handle(PublicationsRemovedExternal $event) {
        $repo_id = $event->repo_id;
        $external_ids = array_map(function ($id) {
            return strval($id);
        }, $event->publication_ids);
        DB::beginTransaction();

        $idMaps = $this->publicationService->retrieveExternalIds($external_ids);
        $publication_ids = $idMaps->get()->pluck('publication_id');
        $idMaps->delete();
        $this->projectService->removePublicationsByRepoId($repo_id, $publication_ids);
        $this->projectFormService->removePublicationsByRepoId($repo_id, $publication_ids);

        DB::commit();
        $event->message->ack();
    }
}
