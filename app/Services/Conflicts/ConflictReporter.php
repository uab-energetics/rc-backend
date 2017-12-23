<?php
/**
 * Created by IntelliJ IDEA.
 * User: Chris Rocco
 * Date: 2017-12-23
 * Time: 02:20
 */

namespace App\Services\Conflicts;


use App\Encoding;

class ConflictReporter {

    static function getReport($encoding_id){
        $encoding = Encoding::find($encoding_id);
        $questions = $encoding->form->questions;
        $other_encodings = $encoding->form->encodings()->where('id', '!=', $encoding_id)->get();

        $_encoding = $encoding->getResponseTable();
        $_others = [];
        foreach ($other_encodings as $_other){
            $_others[] = $_other->getResponseTable();
        }
        $_questions = $questions->toArray();

        return [
            'encoding' => $_encoding,
            'other_encodings' => $_others,
            'questions' => $_questions
        ];
    }

}