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
            return response("Invalid Input", 400);
        }
    }
}
