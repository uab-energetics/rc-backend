<?php

namespace App\Http\Controllers;

use App\Services\Encodings\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller {

    public function assignOne(Request $request, AssignmentService $assignmentService) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:forms,id',
            'project_id' => 'exists:projects,id',
        ]);

        DB::beginTransaction();
            $encoding = $assignmentService->assignTo($request->form_id, $request->publication_id, $request->user_id);
        DB::commit();

        return $encoding;
    }

}
