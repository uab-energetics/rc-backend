<?php

namespace App\Services\Projects;

use App\Form;
use App\FormPublication;
use App\Project;
use App\ProjectForm;
use App\ProjectPublication;
use App\ProjectResearcher;
use App\Publication;
use App\Services\Forms\FormService;
use App\User;

class ProjectService {

    public function makeProject($params) {
        return Project::create($params);
    }

    public function search($query) {
        return Project::search($query)->paginate(getPaginationLimit())->toArray()['data'];
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

    public function addEncoder(Project $project, User $encoder) {

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
        return ProjectForm::upsert([
            'project_id' => $project->getKey(),
            'form_id' => $form->getKey(),
        ]);
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
        return $project->forms()->without('rootCategory')->get();
    }

    /** @var FormService */
    private $formService;

    public function __construct(FormService $formService) {
        $this->formService = $formService;
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