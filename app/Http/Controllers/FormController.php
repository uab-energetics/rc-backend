<?php

namespace App\Http\Controllers;

use App\Category;
use App\Form;
use App\Models\Question;
use App\Project;
use App\Rules\FormType;
use App\Services\Forms\FormService;
use App\Services\Projects\ProjectService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FormController extends Controller {

    public function create(Project $project, Request $request, ProjectService $projService, FormService $formService) {
        $validator = $this->createValidator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

        $form = null;
        DB::transaction(function () use (&$form, &$request, &$project, &$projService, &$formService) {
            $form = $formService->makeForm($request->all());
            $edge = $projService->addForm($project, $form);
        });

        return $form->toArray();
    }

    public function delete(Form $form, FormService $formService) {
        $res = $formService->deleteForm($form);
        return okMessage("Successfully deleted form");
    }

    public function addQuestion(Form $form, Question $question, Request $request, FormService $formService) {
        $category = $this->findCategory($form, $request->category_id);
        if ($category === false) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }

        DB::transaction( function() use (&$form, &$question, &$category, &$formService, $request) {
            $formService->addQuestion($form, $question, $category);
        });

        return okMessage("Successfully added question to form", 201);
    }

    public function moveQuestion (Form $form, Question $question, Request $request, FormService $formService) {
        $category = $this->findCategory($form, $request->category_id);
        if ($category === false) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }

        $res = $formService->moveQuestion($form, $question, $category);

        if ($res === false) {
            return response()->json(static::INVALID_QUESTION, 403);
        }

        return okMessage("Successfully moved question", 201);
    }

    /**
     * @param Form $form
     * @param $category_id
     * @return Category | false
     */
    protected function findCategory(Form $form, $category_id) {
        $category = Category::find( $category_id );
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }
        if ($form->getKey() !== $category->getForm()->getKey()) {
            return false;
        }
        return $category;
    }

    protected function createForm($params) {
        return Form::create($params);
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

    const INVALID_CATEGORY = [
        'status' => 'INVALID_CATEGORY',
        'msg' => "The specified category does not belong to the specified form"
    ];

    const INVALID_QUESTION = [
        'status' => 'INVALID_QUESTION',
        'msg' => "The specified question isn't already in the specified form."
    ];
}
