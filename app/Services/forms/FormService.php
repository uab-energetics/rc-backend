<?php


namespace App\Services\Forms;


use App\Category;
use App\Form;
use App\FormQuestion;
use App\Models\Question;

class FormService {

    public function makeForm($params) {
        $category = Category::create([
            'parent_id' => null,
            'name' => 'root',
        ]);

        $params['root_category_id'] = $category->getKey();
        $params['published'] = false;
        $form = Form::create($params);

        return $form;
    }

    public function addQuestion(Form $form, Question $question) {
        return FormQuestion::create([
            'form_id' => $form->getKey(),
            'question_id' => $question->getKey(),
        ]);
    }

}