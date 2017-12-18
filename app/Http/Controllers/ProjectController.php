<?php

namespace App\Http\Controllers;

use App\ProjectResearcher;
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

        return $project->toArray();
    }

    public function retrieve(Project $project) {
        return $project->toArray();
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

}
