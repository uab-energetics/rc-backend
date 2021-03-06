<?php

namespace App\Services\Projects;

use App\Events\ProjectCreated;
use App\Exceptions\ProjectResearcherCountException;
use App\Form;
use App\FormPublication;
use App\Project;
use App\ProjectEncoder;
use App\ProjectForm;
use App\ProjectPublication;
use App\ProjectResearcher;
use App\Publication;
use App\Services\Forms\FormService;
use App\Services\ProjectForms\ProjectFormService;
use App\Services\Repositories\PubRepoService;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ProjectService {

    public function makeProject($params, User $user) {
        $project = Project::create($params);
        $this->makePublicationRepo($project);
        event(new ProjectCreated($project, $user));
        return $project;
    }

    public function retrieveByRepoId($repo_id) {
        return Project::query()
            ->where('repo_uuid', '=', $repo_id);
    }

    public function search($search) {
        return search(Project::query(), $search, Project::searchable);
    }

    public function updateProject(Project $project, $params) {
        return $project->update($params);
    }

    public function deleteProject(Project $project) {
        $forms = $project->forms()->get();
        foreach ($forms as $form) {
            $this->formService->deleteForm($form);
        }
        $project->delete();
    }

    public function addResearcher($project_id, $user_id, $isOwner = false) {
        $exists = ProjectResearcher::where([
            ['project_id', '=', $project_id],
            ['researcher_id', '=', $user_id]
        ])->count();
        if($exists > 0) return null;

        return ProjectResearcher::upsert([
            'project_id' => $project_id,
            'researcher_id' => $user_id
        ]);
    }

    public function removeResearcher(Project $project, User $user, $force = false) {
        $edge = $this->getResearcherEdge($project->getKey(), $user->getKey());
        $researcherCount = ProjectResearcher::query()->count();
        if ($researcherCount === 1) {
            if ($force === true) {
                $this->deleteProject($project);
                return;
            }
            throw new ProjectResearcherCountException();
        }
        $edge->delete();
    }

    public function addEncoder(Project $project, User $encoder) {
        $edge = ProjectEncoder::upsert([
            'project_id' => $project->getKey(),
            'coder_id' => $encoder->getKey(),
        ]);

        $forms = $project->forms()->where('project_form.auto_enroll', '=', true)->get();
        foreach ($forms as $form) {
            $this->projectFormService->addEncoder($project, $form, $encoder);
        }

        return $edge;
    }

    public function removeEncoder(Project $project, User $user) {
        $edge = $this->getEncoderEdge($project->getKey(), $user->getKey());
        $edge->delete();

        $forms = $project->forms()->without(['rootCategory', 'questions'])->get();
        foreach ($forms as $form) {
            $this->projectFormService->removeEncoder($project, $form, $user);
        }
    }

    public function getProjectsByUser(User $user) {
        $user_id = $user->getKey();
        return Project::query()
            ->whereHas('researchers', function($query) use ($user_id) {
                $query->where('researcher_id', '=', $user_id);
            })
            ->orWhereHas('encoders', function ($query) use ($user_id) {
                $query->where('coder_id', '=', $user_id);
            });
    }

    public function searchResearchers(Project $project, $search = null) {
        return search($project->researchers(), $search, User::searchable)
            ->paginate(getPaginationLimit())->toArray()['data'];
    }

    public function searchEncoders(Project $project, $search = null) {
        return search($project->encoders(), $search, User::searchable)
            ->paginate(getPaginationLimit())->toArray()['data'];
    }

    public function addForm (Project $project, Form $form) {
        return $this->projectFormService->makeProjectForm($project, $form);
    }

    public function addPublicationsByRepoId($repo_id, $publications) {
        $projects = $this->retrieveByRepoId($repo_id)->get();
        foreach ($projects as $project) {
            $this->addPublications($project, $publications);
        }
    }

    public function removePublicationsByRepoId($repo_id, $publication_ids) {
        $projects = $this->retrieveByRepoId($repo_id)->get();
        foreach ($projects as $project) {
            ProjectPublication::query()
                ->where('project_id', $project->getKey())
                ->whereIn('publication_id', $publication_ids)
                ->delete();
        }
    }

    public function addPublications(Project $project, $publications) {
        /** @var Publication $publication */
        foreach ($publications as $publication) {
            $this->addPublication($project, $publication);
        }
    }

    public function addPublication(Project $project, Publication $publication) {
        $edge = ProjectPublication::upsert([
            'project_id' => $project->getKey(),
            'publication_id' => $publication->getKey(),
        ]);
        return $edge;
    }

    public function removePublication(Project $project, Publication $publication) {
        $edge = ProjectPublication::query()
            ->where('project_id', '=', $project->getKey())
            ->where('publication_id', '=', $publication->getKey())
            ->first();
        if ($edge === null) return false;
        $edge->delete();

        return true;
    }

    public function getForms(Project $project) {
        return $project->forms()
            ->with('projectForms')
            ->withPivot('repo_uuid')
            ->without(['rootCategory', 'questions'])
            ->get();
    }

    public function handleUserDeleted(User $user) {
        $projects = $this->getProjectsByUser($user)->get();
        foreach ($projects as $project) {
            if ($this->isResearcher($project->getKey(), $user->getKey())) {
                $this->removeResearcher($project, $user, true);
            }
            if ($this->isEncoder($project->getKey(), $user->getKey())) {
                $this->removeEncoder($project, $user);
            }
        }
    }

    /**
     * @param PubRepoService $service
     * @param Project $project
     * @throws RequestException
     */
    public function makePublicationRepo(Project $project) {
        /** @var PubRepoService $service */
        $service = app()->make(PubRepoService::class);
        $repo = $service->createRepo($project->getKey(), "Main Repository");
        $project->repo_uuid = $repo['id'];
        $project->save();
    }

    public function handleRepoDeleted($repo_id) {
        $projects = $this->retrieveByRepoId($repo_id)->get();
        foreach($projects as $project) {
            $project->repo_uuid = null;
            $project->save();
        }
    }

    private function getResearcherEdge($project_id, $user_id) {
        return ProjectResearcher::query()
            ->where('project_id', '=', $project_id)
            ->where('researcher_id', '=', $user_id)
            ->firstOrFail();
    }

    private function isResearcher($project_id, $user_id) {
        return ProjectResearcher::query()
            ->where('project_id', '=', $project_id)
            ->where('researcher_id', '=', $user_id)
            ->count() > 0;
    }

    private function getEncoderEdge($project_id, $user_id) {
        return ProjectEncoder::query()
            ->where('project_id', '=', $project_id)
            ->where('coder_id', '=', $user_id)
            ->firstOrFail();
    }

    private function isEncoder($project_id, $user_id) {
        return ProjectEncoder::query()
            ->where('project_id', '=', $project_id)
            ->where('coder_id', '=', $user_id)
            ->count() > 0;
    }

    /** @var FormService */
    private $formService;
    /** @var ProjectFormService  */
    protected $projectFormService;

    public function __construct(FormService $formService, ProjectFormService $projectFormService) {
        $this->formService = $formService;
        $this->projectFormService = $projectFormService;
    }

    public function getPublications(Project $project, $query = null) {
        if ($query === null) {
            return $project->publications()->get();
        }
        return $project->publications()
            ->where('publications.name', 'LIKE', "%$query%")
            ->orWhere('publications.embedding_url', 'LIKE', "%$query%")
            ->get();
    }

}
