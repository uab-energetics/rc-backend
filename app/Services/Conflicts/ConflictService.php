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
        if($my_response['type'] && $their_response['type']){
            $this->fail_message = "different types";
            return false;
        }
        switch ($my_response['type']){
            case RESPONSE_TEXT:
                return $my_response['txt'] === $their_response['txt'];
            case RESPONSE_BOOL:
                return $my_response['boo'] === $their_response['boo'];
            case RESPONSE_NUMBER:
                return $my_response['num'] === $their_response['num'];
            case RESPONSE_SELECT:
                return $my_response['sel'] === $their_response['sel'];
            case RESPONSE_MULTI_SELECT:
                $this->fail_message = "Didn't feel like comparing multi-selects";
                return false;
        }
        $this->fail_message = "Unable to compare type";
        return false;
    }

    protected function lookupResponse($encoding, $question){
        return '';
    }

}