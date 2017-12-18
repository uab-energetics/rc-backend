<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller {

    public function create (Request $request) {
        try {
            $question = Question::createWithRel($request->all());
            return $question->toArray();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'INVALID_PARAMS',
                'msg' => "Invalid form data"
            ], 400);
        }
    }
}
