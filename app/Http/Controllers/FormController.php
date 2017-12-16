<?php

namespace App\Http\Controllers;

use App\Project;
use App\Services\ProjectService\ProjectService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class FormController extends Controller {

    public function create(Project $project, Request $request, ProjectService $projService) {
        $validator = $this->createValidator($request->all());
        if ($validator->fails()) {
            return response()->json([
                'status' => 'INVALID_PARAMS',
                'msg' => "Invalid parameters",
                'reasons' => $validator->errors()
            ], 400);
        }


    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator($data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'string',
            'type' => 'required|string',
        ]);
    }
}
