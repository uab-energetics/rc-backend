<?php

namespace App\Http\Controllers;

use App\Services\Encodings\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller {

    public function assignOne(Request $request, AssignmentService $assignmentService) {
        $params = $request->all();
        $validator = $this->assignOneValidator($params);
        if ($validator->fails()) return invalidParamMessage($validator);

        DB::beginTransaction();
            $encoding = $assignmentService->assignTo($request->form_id, $request->publication_id, $request->user_id, $request->project_id);
        DB::commit();

        return $encoding;
    }


    protected function assignOneValidator($data) {
        return Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:forms,id',
            'publication_id' => 'required|exists:publications,id',
            'project_id' => 'exists:projects,id',
        ]);
    }

}
