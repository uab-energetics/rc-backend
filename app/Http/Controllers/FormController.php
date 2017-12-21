<?php

namespace App\Http\Controllers;

use App\Category;
use App\Form;
use App\Models\Question;
use App\Project;
use App\Rules\FormType;
use App\Services\Forms\FormService;
use App\Services\Projects\ProjectService;
use App\Services\Questions\QuestionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FormController extends Controller {

    public function create(Project $project, Request $request,
           ProjectService $projService, FormService $formService) {
        $validator = $this->createValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

        $form = null;
        DB::beginTransaction();
            $form = $formService->makeForm($request->all());
            $edge = $projService->addForm($project, $form);
        DB::commit();

        return $form->refresh();
    }

    public function retrieve(Form $form) {
        return $form;
    }

    public function search(Request $request, FormService $formService) {
        $validator = simpleSearchValidator($request->all());
        if ($validator->fails()) return invalidParamMessage($validator);
        return $formService->search($request->search);
    }

    public function update (Form $form, Request $request, FormService $formService) {
        $params = $request->all();
        $validator = $this->updateValidator($params);
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

        DB::beginTransaction();
            $formService->updateForm($form, $params);
        DB::commit();

        return $form->refresh();
    }

    public function delete(Form $form, FormService $formService) {
        $res = $formService->deleteForm($form);
        return okMessage("Successfully deleted form");
    }

    public function addQuestion(Form $form, Question $question, Request $request, FormService $formService) {
        $category = $formService->findCategory($form, $request->category_id);
        if ($category === false) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }

        DB::beginTransaction();
            $formService->addQuestion($form, $question, $category);
        DB::commit();

        $form->refresh();
        return $form;
    }

    public function moveQuestion (Form $form, Question $question, Request $request, FormService $formService) {
        $category = $formService->findCategory($form, $request->category_id);
        if ($category === false) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }

        $res = $formService->moveQuestion($form, $question, $category);

        if ($res === false) {
            return response()->json(static::INVALID_QUESTION, 403);
        }

        $form->refresh();
        return $form;
    }

    public function removeQuestion(Form $form, Question $question, FormService $formService) {
        $res = $formService->removeQuestion($form, $question);
        if ($res === false) {
            return response()->json([
                'status' => 'QUESTION_NOT_FOUND',
                'msg' => "The specified question wasn't found in the specified form"
            ], 404);
        }
        return okMessage("Successfully remove question from form");
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator($data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'string',
            'type' => ['required', new FormType()],
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function updateValidator($data) {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'description' => 'string',
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
