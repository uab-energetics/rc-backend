<?php

namespace App\Services\Projects;

use App\Form;
use App\Project;
use App\ProjectForm;
use App\User;

class ProjectService {

    public function makeProject($params) {
        return Project::create($params);
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

}