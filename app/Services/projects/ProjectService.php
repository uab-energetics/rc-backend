<?php

namespace App\Services\Projects;

use App\Form;
use App\Project;
use App\ProjectForm;
use App\ProjectResearcher;
use App\Services\Forms\FormService;
use App\User;

class ProjectService {

    public function makeProject($params) {
        return Project::create($params);
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

    public function addResearcher(Project $project, User $user, $isOwner = false) {
        return ProjectResearcher::create([
            'project_id' => $project->getKey(),
            'researcher_id' => $user->getKey()
        ]);
    }

    public function addForm (Project $project, Form $form) {
        return ProjectForm::create([
            'project_id' => $project->getKey(),
            'form_id' => $form->getKey(),
        ]);
    }

    public function getForms(Project $project) {
        return $project->forms()->without('rootCategory')->get();
    }

    /** @var FormService */
    private $formService;

    public function __construct(FormService $formService) {
        $this->formService = $formService;
    }

}