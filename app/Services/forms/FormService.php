<?php


namespace App\Services\Forms;


use App\Category;
use App\Form;
use App\FormQuestion;
use App\Models\Question;
use App\CategoryQuestion;

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

    public function addQuestion(Form $form, Question $question, Category $category = null) {
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }

        $categoryQuestion = CategoryQuestion::create([
            'category_id' => $category->getKey(),
            'question_id' => $question->getKey(),
        ]);

        $formQuestion = FormQuestion::create([
            'form_id' => $form->getKey(),
            'question_id' => $question->getKey(),
        ]);

        return $formQuestion;
    }

}