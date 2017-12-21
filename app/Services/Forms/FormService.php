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

    public function search($query) {
        return Form::search($query)->get();
    }

    public function updateForm(Form $form, $params) {
        unset($params['root_category_id']);
        unset($params['type']);
        $form->update($params);
    }

    public function deleteForm(Form $form) {
        $rootCategory = $form->rootCategory()->first();
        $form->delete();
        $rootCategory->delete();
        return true;
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

    public function removeQuestion(Form $form, Question $question) {
        $edge = FormQuestion::query()
            ->where('form_id', '=', $form->getKey())
            ->where('question_id', '=', $question->getKey())
            ->first();

        if ($edge === null) {
            return false;
        }
        $edge->delete();
        return true;
    }

    /**
     * @param Form $form
     * @param $category_id
     * @return Category | false
     */
    public function findCategory(Form $form, $category_id) {
        $category = Category::find( $category_id );
        if ($category === null) {
            $category = $form->rootCategory()->first();
        }
        if ($form->getKey() !== $category->getForm()->getKey()) {
            return false;
        }
        return $category;
    }

}