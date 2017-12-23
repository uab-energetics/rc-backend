<?php
/**
 * Created by IntelliJ IDEA.
 * User: Chris Rocco
 * Date: 2017-12-23
 * Time: 02:20
 */

namespace App\Services\Conflicts;


use App\Encoding;

class ConflictService {

    protected $fail_message;

    function runConflictScan($encoding_id){
        $encoding = Encoding::find($encoding_id);
        $form = $encoding->form;
        $other_encodings = $form->encodings()->where('id', '!=', $encoding_id)->get();
        $questions = $form->questions;

        foreach($other_encodings as $other_encoding){
            foreach($questions as $question){
                $my_answer = $encoding->lookupAnswer($question);
                $their_answer = $other_encoding->lookupAnswer($question);
                if($my_answer && $their_answer){
                    $result = $this->compareAnswers($my_answer, $their_answer);
                    if(!$result){
                        // handle disagree
                    } else {
                        // handle agree
                    }
                }
            }
        }
    }

    function compareAnswers($my_response, $their_response){
        $this->fail_message = "always disagree";
        return false;
//        return $my_response['type'] === $their_response['type'];
    }

}