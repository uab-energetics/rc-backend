<?php

namespace App\Http\Controllers;

use App\Form;
use App\Models\Question;
use App\Rules\QuestionRule;
use App\Rules\ResponseType;
use App\Services\Forms\FormService;
use App\Services\Questions\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller {

    public function create (Request $request, QuestionService $questionService) {
        $validator = $this->createValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }
        DB::beginTransaction();
            $question = $questionService->makeQuestion($request->all());
        DB::commit();

        $question->load(['options', 'accepts']);
        return $question->refresh();
    }

    public function createQuestion(Form $form, Request $request,
               FormService $formService, QuestionService $questionService)
    {
        $validator = $this->addToFormValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }
        $category = $formService->findCategory($form, $request->category_id);
        if ($category === false) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }


        DB::beginTransaction();
            $question = $questionService->makeQuestion($request->get('question'));
            $formService->addQuestion($form, $question, $category);
        DB::commit();

        return $form->refresh();
    }

    public function search(Request $request, QuestionService $questionService) {
        $validator = simpleSearchValidator($request->all());
        if ($validator->fails()) return invalidParamMessage($validator);
        return $questionService->search($request->search)->get();
    }

    public function retrieve(Question $question) {
        return $question;
    }

    public function update(Question $question, Request $request, QuestionService $questionService) {
        $validator = $this->updateValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }
        DB::beginTransaction();
            $questionService->updateQuestion($question, $request->all());
        DB::commit();

        return $question->refresh();
    }

    public function delete(Question $question, QuestionService $questionService) {
        $questionService->deleteQuestion($question);
        return okMessage("Successfully deleted question");
    }

    protected function createValidator($data) {
        return QuestionRule::newQuestionValidator($data);
    }

    protected function updateValidator($data) {
        return QuestionRule::existingQuestionValidator($data);
    }


    protected function addToFormValidator($data) {
        return Validator::make($data, [
            'category_id' => 'nullable|exists:categories,id',
            'question' => ['required', new QuestionRule()]
        ]);
    }

    const INVALID_CATEGORY = [
        'status' => 'INVALID_CATEGORY',
        'msg' => "The specified category does not belong to the specified form"
    ];

    const INVALID_QUESTION = [
        'status' => 'INVALID_QUESTION',
        'msg' => "The specified question isn't already in the specified form."
    ];
}
