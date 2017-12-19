<?php


namespace App\Services\Questions;


use App\Models\Question;

class QuestionService {

    public function makeQuestion($params) {
        $question = Question::create($params);
        $this->addSubRelations($question, $params);
        return $question;
    }

    public function updateQuestion(Question $question, $params) {
        $question->update($params);
        $this->deleteSubRelations($question);
        $this->addSubRelations($question, $params);
        return $question;
    }

    public function deleteQuestion(Question $question) {
        $this->deleteSubRelations($question);
        $question->delete();
    }

    public function addSubRelations(Question $question, $params) {
        $question->saveOptions(getOrDefault($params['options'], []));
        $question->saveAccepts(getOrDefault($params['accepts'], []));
    }

    public function deleteSubRelations(Question $question) {
        $question->options()->delete();
        $question->accepts()->delete();
    }


}