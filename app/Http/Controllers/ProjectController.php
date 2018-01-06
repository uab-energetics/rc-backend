<?php

namespace App\Http\Controllers;

use App\Notifications\InvitedToProject;
use App\ProjectResearcher;
use App\Publication;
use App\Services\Projects\ProjectService;
use App\User;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller {

    public function create(Request $request, ProjectService $projectService) {
        $user = $request->user();

        DB::beginTransaction();
            $project = $projectService->makeProject($request->all());
            $projectService->addResearcher($project->getKey(), $user->getKey(), true);
        DB::commit();

        return $project;
    }

    public function update(Project $project, Request $request, ProjectService $projectService) {
        DB::beginTransaction();
            $projectService->updateProject($project, $request->all());
        DB::commit();

        return $project->refresh();
    }

    public function retrieve(Project $project) {
        return $project;
    }

    public function search(Request $request, ProjectService $projectService) {
        $validator = simpleSearchValidator($request->all());
        if ($validator->fails()) return invalidParamMessage($validator);
        return $projectService->search($request->search);
    }

    public function delete(Project $project, ProjectService $projectService) {
        DB::beginTransaction();
            $projectService->deleteProject($project);
        DB::commit();
        return okMessage("Successfully deleted project");
    }

    public function retrieveForms(Project $project, ProjectService $projectService) {
        return $projectService->getForms($project);
    }

    public function retrievePublications(Project $project, Request $request, ProjectService $projectService) {
        return search(
            $project->publications(),
            request('search'),
            Publication::searchable
        )->paginate(request('page_size', 500));
    }

    public function addPublication(Project $project, Publication $publication, ProjectService $projectService) {
        $projectService->addPublication($project, $publication);
        return okMessage("Successfully added publication to project");
    }

    public function removePublication(Project $project, Publication $publication, ProjectService $projectService) {
        $res = $projectService->removePublication($project, $publication);
        if ($res === false) {
            return response()->json(static::PUBLICATION_NOT_FOUND, 404);
        }
        return okMessage("Successfully removed the publication");
    }

    public function inviteResearcher(Project $project, Request $request, ProjectService $projectService){
        $user = User::find($request->user_id);
        if(!$user) return response("no user with that id", 404);

        // TODO - introduce access levels
        $res = $projectService->addResearcher($project->id, $request->user_id);
        if(!$res){
            return response()->json([
                'msg' => 'That user is already in this project!'
            ], 409);
        }

        $user->notify(new InvitedToProject($project->id, $request->notification_payload));
        return response()->json([
            'msg' => "User invited to collaborate!"
        ], 200);
    }

    public function searchResearchers(Project $project, Request $request){
        $request->validate(['search' => 'string|nullable']);
        return $this->service->searchResearchers($project, $request->search);
    }

    public function searchEncoders(Project $project, Request $request) {
        $request->validate(['search' => 'string|nullable']);
        return $this->service->searchEncoders($project, $request->search);
    }

    /** @var ProjectService  */
    protected $service;

    public function __construct(ProjectService $projectService) {
        $this->service = $projectService;
    }

    const PUBLICATION_NOT_FOUND = [
        'status' => 'RESOURCE_NOT_FOUND',
        'msg' => 'The specified project does not have the specified publication'
    ];
}
