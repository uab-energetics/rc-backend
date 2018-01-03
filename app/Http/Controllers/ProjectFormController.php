<?php

namespace App\Http\Controllers;

use App\Form;
use App\Project;
use App\Publication;
use App\Services\ProjectForms\ProjectFormService;
use App\Services\Publications\PublicationService;
use App\Services\Users\UserService;
use App\User;
use Illuminate\Http\Request;

class ProjectFormController extends Controller {

    public function searchPublications(Project $project, Form $form, Request $request) {
        $request->validate(['search' => 'string|nullable']);
        return $this->service->retrievePublications($project, $form, $request->search);
    }

    public function searchEncoders(Project $project, Form $form, Request $request) {
        $request->validate(['search' => 'string|nullable']);
        return $this->service->retrieveEncoders($project, $form, $request->search);
    }

    public function addPublication(Project $project, Form $form, Publication $publication, Request $request) {
        $request->validate(['priority' => 'nullable|integer']);
        return $this->service->addPublication($project, $form, $publication, $request->priority);
    }

    public function addPublications(Project $project, Form $form, Request $request, PublicationService $pubService) {
        $request->validate([
            'publications.*' => 'exists:publications,id',
            'priority' => 'nullable|integer'
        ]);
        $publications = collect($request->publications);
        $publications = $publications->map(function($pubID) use ($pubService) {
            return $pubService->getPublication($pubID);
        });
        $this->service->addPublications($project, $form, $publications, $request->priority);
        return okMessage("Successfully added publications");
    }

    public function addEncoder(Project $project, Form $form, User $user) {
        return $this->service->addEncoder($project, $form, $user);
    }

    public function addEncoders(Project $project, Form $form, Request $request, UserService $userService) {
        $request->validate(['users.*' => 'exists:users,id']);
        $users = collect($request->encoders);
        $users = $users->map(function($userID) use ($userService) {
            return $userService->retrieve($userID);
        });
        $this->service->addEncoders($project, $form, $users);
        return okMessage("Successfully added encoders");
    }


    /** @var ProjectFormService  */
    protected $service;

    public function __construct(ProjectFormService $service) {
        $this->service = $service;
    }
}
