<?php


namespace App\Services\Conflicts;


use App\ConflictRecord;
use App\Encoding;
use App\User;

class ConflictReporter {

    static function getReport($encoding_id){
        $encoding = Encoding::find($encoding_id);
        $questions = $encoding->form->questions;
        $other_encodings = $encoding->collaborators()->with('owner')->get();
        $conflict_records = ConflictRecord::where('encoding_id', '=', $encoding_id)->get()->toArray();

        /* map conflict records to hash table */
        $_conflict_records = [];
        foreach ($conflict_records as $record){
            $qid = $record['question_id'];
            $other_encoding_id = $record['other_encoding_id'];
            $_conflict_records[$qid][$other_encoding_id] = $record;
        }

        return [
            'encoding' => $encoding,
            'other_encodings' => $other_encodings,
            'questions' => $questions,
            'conflicts' => $_conflict_records
        ];
    }

}