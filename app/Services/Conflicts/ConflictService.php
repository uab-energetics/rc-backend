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

        $conflict_records = [];
        foreach($other_encodings as $other_encoding){
            foreach($questions as $question){
                $my_answer = $this->lookupResponse($encoding, $question);
                $their_answer = $this->lookupResponse($other_encoding, $question);
                if($my_answer && $their_answer){
                    $doesAgree = $this->compareAnswers($my_answer, $their_answer);
                    $status = true;
                    $message = '';
                    if(!$doesAgree){
                        $status = false;
                        $message = $this->fail_message;
                    }
                    $conflict_records[] = [ // that push syntax though
                        'user_encoding_id' => $encoding_id,
                        'other_encoding_id' => $other_encoding->id,
                        'question' => $question->id,
                        'status' => $status,
                        'msg' => $message
                    ];
                    $this->fail_message = '';
                }
            }
        }
        // save all conflict records in DB in one query.
    }

    /**
     * Compares two responses for agreement.
     * May set a descriptive message on disagreement.
     * @param $my_response
     * @param $their_response
     * @return bool
     */
    function compareAnswers($my_response, $their_response){
        $this->fail_message = "always disagree";
        return false;
    }

    protected function lookupResponse($encoding, $question){
        return '';
    }

}