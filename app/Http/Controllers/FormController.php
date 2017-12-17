<?php

namespace App\Http\Controllers;

use App\Form;
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
}
