<?php

namespace App\Services\Conflicts;


use App\ConflictRecord;
use App\Encoding;

class ConflictScanner {

    private $found_conflict = false;
    protected $fail_message;

    /**
     * Runs a conflict scan and inserts the records in the DB.
     * @param $encoding_id
     * @return array
     */
    public function runConflictScan($encoding_id){
        $encoding = Encoding::find($encoding_id);
        $form = $encoding->form;
        $other_encoding_models = $encoding->collaborators;
        /* map to more useful format */
        $encoding = $encoding->getResponseTable();
        $other_encodings = [];
        foreach ($other_encoding_models as $_enc){
            $other_encodings[] = $_enc->getResponseTable();
        }
        $questions = $form->questions->toArray();

        $conflict_records = [];
        foreach($other_encodings as $other_encoding){
            foreach($questions as $question){
                $my_answer = $this->lookupResponse($encoding, $question['id']);
                $their_answer = $this->lookupResponse($other_encoding, $question['id']);
                if($my_answer && $their_answer){
                    $doesAgree = $this->compareAnswers($my_answer, $their_answer);
                    $status = true;
                    $message = '';
                    if(!$doesAgree){
                        $this->found_conflict = true;
                        $status = false;
                        $message = $this->fail_message;
                    }
                    $conflict_records[] = [ // that push syntax though
                        'encoding_id' => $encoding['id'],
                        'other_encoding_id' => $other_encoding['id'],
                        'question_id' => $question['id'],
                        'agrees' => $status,
                        'message' => $message
                    ];
                    $this->fail_message = '';
                }
            }
        }
        // save all conflict records in DB in one query.
        ConflictRecord::where('encoding_id', '=', $encoding_id)->delete(); // must remove old ones.
        ConflictRecord::insert($conflict_records);
        return $conflict_records;
    }

    /**
     * Compares two responses for agreement.
     * May set a descriptive message on disagreement.
     * @param $my_response
     * @param $their_response
     * @return bool
     */
    function compareAnswers($my_response, $their_response){
        if($my_response['type'] !== $their_response['type']){
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

    protected function lookupResponse($encoding, $question_id){
        // it's only comparing the first branch right now.
        $branch_ids = array_keys($encoding['branches']);
        if(!isset($encoding['branches'])) return null;
        if(!isset($encoding['branches'][$branch_ids[0]])) return null;
        if(!isset($encoding['branches'][$branch_ids[0]][$question_id.''])) return null;
        return $encoding['branches'][$branch_ids[0]][$question_id.''];
    }

}