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
        if(!$encoding) return null;
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
            foreach ($encoding['branches'] as $branch_idx => $branch) {
                foreach($questions as $question){
                    $my_answer = $this->lookupResponse($encoding, $question['id'], $branch_idx);
                    $their_answer = $this->lookupResponse($other_encoding, $question['id'], $branch_idx);
                    if(!$my_answer || !$their_answer) {
                        continue;
                    }
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
                        'branch_id' => $branch['id'],
                        'other_branch_id' => $other_encoding['branches'][$branch_idx]['id'],
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
                $mySel = collect($my_response['selections'])->pluck('txt')->toArray();
                $theirSel = collect($their_response['selections'])->pluck('txt')->toArray();
                //added because array_diff only goes one way
                return count(array_diff($mySel, $theirSel)) + count(array_diff($theirSel, $mySel)) === 0;
        }
        $this->fail_message = "Unable to compare type";
        return false;
    }

    protected function lookupResponse($encoding, $question_id, $branch_id = 0){
        // it's only comparing the first branch right now.
        $branch_ids = array_keys($encoding['branches']);
        try {
            return $encoding['branches'][$branch_ids[$branch_id]]['responses'][$question_id.''];
        } catch (\Exception $e) {
            return null;
        }
    }

}