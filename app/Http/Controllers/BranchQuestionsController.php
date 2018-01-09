<?php

namespace App\Http\Controllers;

use App\EncodingExperimentBranch as Branch;
use App\Models\Question;

class BranchQuestionsController extends Controller {

    /**
     * TODO - validate that the question belongs to... the branch's encoding's form's codebook
     * addQuestion ( branch_id, question_id ) ->
     * removeQuestion ( branch_id, question_id ) ->
     */

    function getQuestions(Branch $branch){
        return $branch->questionmap;
    }

    function addQuestion(Branch $branch, Question $question){
        $branch->questionMap()->syncWithoutDetaching($question->getKey());
        return $branch->questionmap;
    }

    function removeQuestion(Branch $branch, Question $question){
        $branch->questionMap()->detach($question->getKey());
        $branch->responses()->where('question_id', '=', $question->getKey())->delete();
        return $branch->questionmap;
    }

}
