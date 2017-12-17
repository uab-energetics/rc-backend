<?php

namespace App\Services\Projects;

use App\Form;
use App\Project;
use App\ProjectForm;

class ProjectService {

    public function addForm (Project $project, Form $form) {
        return ProjectForm::create([
            'project_id' => $project->getKey(),
            'form_id' => $form->getKey(),
        ]);
    }

}