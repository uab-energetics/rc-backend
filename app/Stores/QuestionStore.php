<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/8/17
 * Time: 9:57 PM
 */

namespace App\Stores;


use App\Options;
use App\Question;

class QuestionStore {

    static function create( $data ){
        $question = Question::create($data);
        $question->options()->saveMany(
            QuestionStore::mapOptions(
                Store::get($data['options'], [])
            )
        );
        return QuestionStore::find($question->id);
    }

    static function update( $id, $data ){
        $question = Question::find($id);
        $question->options()->delete();         // delete the options every time
        $question->fill($data);
        $question->options()->saveMany(
            self::mapOptions(
                Store::get($data['options'], [])
            )
        );
        $question->save();
    }

    static function find($id): Question {
        $q = Question::find($id);
        $q->load('options');
        return $q;
    }

    static function all(){
        return Question::with('options')->get();
    }

    private static function mapOptions($strings){
        return array_map(function($opt){
            return new Options([ 'text' => $opt ]);
        }, $strings);
    }
}