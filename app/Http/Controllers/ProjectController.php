<?php

namespace App\Http\Controllers;

use App\ProjectResearcher;
use App\Publication;
use App\Services\Projects\ProjectService;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller {

    public function create(Request $request, ProjectService $projectService) {
        $validator = $this->createValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

        $user = $request->user();

        DB::beginTransaction();
            $project = $projectService->makeProject($request->all());
            $projectService->addResearcher($project, $user, true);
        DB::commit();

        return $project;
    }

    public function update(Project $project, Request $request, ProjectService $projectService) {
        $validator = $this->updateValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

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
        return $projectService->getPublications($project, $request->search);
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

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator($data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'string',
        ]);
    }
    protected function updateValidator($data) {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'description' => 'string',
        ]);
    }

    const PUBLICATION_NOT_FOUND = [
        'status' => 'RESOURCE_NOT_FOUND',
        'msg' => 'The specified project does not have the specified publication'
    ];
}
