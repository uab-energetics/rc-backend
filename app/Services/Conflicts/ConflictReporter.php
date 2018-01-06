<?php


namespace App\Services\Conflicts;


use App\ConflictRecord;
use App\Encoding;
use App\User;

class ConflictReporter {

    static function getReport($encoding_id){
        $encoding = Encoding::find($encoding_id);
        if(!$encoding) return null;
        $questions = $encoding->form->questions;
        $other_encodings = $encoding->collaborators()->with('owner')->get();
        $conflict_records = ConflictRecord::where('encoding_id', '=', $encoding_id)->get()->toArray();

        $branchNames = collect();


        foreach($encoding->toArray()['experiment_branches'] as $branch) {
            $branchNames->push($branch['name']);
        }
        foreach($other_encodings as $other_encoding) {
            foreach($other_encoding->toArray()['experiment_branches'] as $branch) {
                $branchNames->push($branch['name']);
            }
        }

        /* map conflict records to hash table */
        $_conflict_records = [];
        foreach ($conflict_records as $record){
            $bid = $record['branch_name'];
            $qid = $record['question_id'];
            $other_encoding_id = $record['other_encoding_id'];
            $_conflict_records[$bid][$qid][$other_encoding_id] = $record;
        }

        return [
            'encoding' => $encoding,
            'other_encodings' => $other_encodings,
            'questions' => $questions,
            'conflicts' => $_conflict_records,
            'groups' => $branchNames->unique(),
        ];
    }

}