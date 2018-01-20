<?php

namespace App\Http\Controllers;

use App\EncodingExperimentBranch as Branch;
use App\FormQuestion;
use App\Models\Question;
use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;

class BranchQuestionsController extends Controller {

     // TODO - validate that the question belongs to... the branch's encoding's form's codebook

    function getQuestions(Branch $branch, Request $request){
        return $this->service->getBranchQuestions($branch);
    }

    function addQuestion(Branch $branch, Question $question, Request $request){
        $form = $branch->encoding()->firstOrFail()->form()->firstOrFail();
        $exists = FormQuestion::query()
            ->where('form_id', '=', $form->getKey())
            ->where('question_id', '=', $question->getKey())
            ->first() !== null;
        if ($exists === false) {
            return response()->json([
                'status' => 'INVALID_QUESTION',
                'msg' => "The specified question must be in the encoding's form"
            ], 400);
        }
        return $this->service->addBranchQuestion($branch, $question);
    }

    function removeQuestion(Branch $branch, Question $question, Request $request){
        $this->service->removeBranchQuestion($branch, $question);
        return $branch->questionmap;
    }

    /** @var EncodingService  */
    protected $service;

    public function __construct(EncodingService $encodingService) {
        $this->service = $encodingService;
    }

}
