<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
use App\Events\EncodingChanged;
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

    function addQuestion($branch_id, $question_id){
        $branch = Branch::find($branch_id);

        $branch->questionMap()->syncWithoutDetaching($question_id);
        return $branch->questionmap;
    }

    function removeQuestion($branch_id, $question_id){
        $branch = Branch::find($branch_id);

        $branch->questionMap()->detach($question_id);
        return $branch->questionmap;
    }

}
