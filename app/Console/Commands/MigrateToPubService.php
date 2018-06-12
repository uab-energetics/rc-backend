<?php

namespace App\Console\Commands;

use App\Project;
use App\ProjectForm;
use App\Publication;
use App\Services\ProjectForms\ProjectFormService;
use App\Services\Projects\ProjectService;
use App\Services\Repositories\PubRepoService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class MigrateToPubService extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publications:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Migrates each project and projectForms's publications to the publication service";

    /** @var PubRepoService  */
    protected $pubRepoService;
    /** @var ProjectService  */
    protected $projectService;
    /** @var ProjectFormService  */
    protected $projectFormService;

    public function __construct(PubRepoService $pubRepoService, ProjectService $projectService, ProjectFormService $projectFormService) {
        parent::__construct();
        $this->pubRepoService = $pubRepoService;
        $this->projectService = $projectService;
        $this->projectFormService = $projectFormService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $projects = $this->getProjects();
        foreach ($projects as $project) {
            try {
                echo "Project $project->name:" . PHP_EOL . "\tmaking repository... ";
                $this->projectService->makePublicationRepo($project);
                echo "success" . PHP_EOL;
            } catch (RequestException $e) {
                echo "Something went wrong making a project repository" . PHP_EOL . $e->getMessage() . PHP_EOL;
                return 1;
            }
            echo "\tuploading publications: ";
            $this->uploadProjectPublications($project);
            echo PHP_EOL;
        }

        $projectForms = $this->getProjectForms();
        foreach ($projectForms as $projectForm) {
            try {
                echo "ProjectForm: $projectForm->id" . PHP_EOL . "\tmaking repository... ";
                $this->projectFormService->makePublicationRepo($projectForm);
                echo "success" . PHP_EOL;
            } catch (RequestException $e) {
                echo "Something went wrong making a projectForm repository" . PHP_EOL . $e->getMessage() . PHP_EOL;
                return 1;
            }
            echo "\tuploading publications: ";
            $this->uploadProjectFormPublications($projectForm);
            echo PHP_EOL;
        }


        return 0;
    }

    /**
     * @return Project[]|Collection
     */
    private function getProjects() {
        return Project::query()
            ->where('repo_uuid', '=', null)
            ->get();
    }

    /**
     * @return ProjectForm|Collection
     */
    private function getProjectForms() {
        return ProjectForm::query()
            ->where('repo_uuid', '=', null)
            ->with(['form' => function ($subquery) {
                $subquery->without(['rootCategory', 'questions']);
            }])
            ->whereHas('form', function ($subquery) {
                $subquery->where('deleted_at', '=', null);
            })
            ->get();
    }



    private function uploadProjectPublications(Project $project) {
        $publicationChunks = $project->publications()->get()->chunk(20);
        foreach ($publicationChunks as $publications) {
            $transPublications = $this->transformPublications($publications->toArray());
            $this->pubRepoService->addPublications($project->getKey(), $project->repo_uuid, array_values($transPublications));
            echo ".";
        }
    }

    private function uploadProjectFormPublications(ProjectForm $projectForm) {
        $publicationChunks =$projectForm->publications()->get()->chunk(20);
        foreach ($publicationChunks as $publications) {
            $transPublications = $this->transformPublications($publications->toArray());
            $this->pubRepoService->addPublications($projectForm->getKey(), $projectForm->repo_uuid, array_values($transPublications));
            echo ".";
        }
    }

    private function transformPublications($publications) {
        return array_map(function ($publication) {
            return [
                'title' => $publication['name'],
                'embeddingURL' => $publication['embedding_url'],
                'sourceID' => $publication['source_id'],
                'uuid' => $publication['uuid'],
            ];
        }, $publications);
    }
}
