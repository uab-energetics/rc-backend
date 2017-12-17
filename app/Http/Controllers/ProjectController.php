<?php

namespace App\Http\Controllers;

use App\ProjectResearcher;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller {

    public function create(Request $request) {
        $user = $request->user();
        $params = $request->all();

        $project = null;
        DB::transaction(function () use(&$params, &$user, &$project){
            $project = Project::create($params);
            $edge = ProjectResearcher::create([
                'project_id' => $project->getKey(),
                'researcher_id' => $user->getKey()
            ]);
        });

        if ($project === null) {
            return response()->json([
                'status' => 'INVALID_PARAMS',
                'msg' => "Something went wrong"
            ], 400);
        }

        return $project->toArray();
    }

}
