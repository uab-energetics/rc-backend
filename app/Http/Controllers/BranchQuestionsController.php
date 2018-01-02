<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
use App\Events\EncodingChanged;
use App\Models\Question;
use App\Rules\ResponseType;
use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchQuestionsController extends Controller {

    /**
     * TODO - validate that the question belongs to... the branch's encoding's form's codebook
     * addQuestion ( branch_id, question_id ) -> branch
     * removeQuestion ( branch_id, question_id ) -> branch
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
        return $branch->questionmap;
    }

}
