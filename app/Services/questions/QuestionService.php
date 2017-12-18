<?php


namespace App\Services\Questions;


use App\Models\Question;

class QuestionService {

    public function makeQuestion($params) {
        return Question::createWithRel($params);
    }


}