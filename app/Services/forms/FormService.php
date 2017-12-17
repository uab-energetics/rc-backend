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

    public function addQuestion(Form $form, Question $question, Category $category = null) {
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }

        $formQuestion = FormQuestion::create([
            'form_id' => $form->getKey(),
            'question_id' => $question->getKey(),
            'category_id' => $category->getKey(),
        ]);

        return $formQuestion;
    }

    public function moveQuestion(Form $form, Question $question, Category $category) {
        $edge = FormQuestion::query()
            ->where('form_id', '=', $form->getKey())
            ->where('question_id', '=', $question->getKey())
            ->first();

        if ($edge === null) {
            return false;
        }

        $edge->category_id = $category->getKey();
        $edge->save();
        return true;
    }

}