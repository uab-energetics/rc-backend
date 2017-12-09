<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    protected $fillable = ['txt'];
    protected $with = ['options', 'accepts'];

    function options() {
        return $this->hasMany(Options::class, 'question_id');
    }

    function responses() {
        return $this->hasMany(Response::class, 'question_id');
    }

    function accepts(){
        return $this->hasMany(AcceptsFormat::class, 'question_id');
    }

    /* helpers */

    public function saveOptions( $str_arr ){
        parent::save();                         // make sure the question exists in DB
        $this->options()->delete();             // delete any existing options
        $opts = array_map(function($str){       // convert the string array to a column -> value array
            return [ 'txt' => $str ];
        }, $str_arr );
        $this->options()->createMany($opts);    // create with FK references
    }

    public function saveAccepts( $str_arr ){
        parent::save();
        $this->accepts()->delete();
        $formats = array_map(function($str){
            return [ 'type' => $str ];
        }, $str_arr);
        $this->accepts()->createMany($formats);
    }

    public static function createWithRel($data){
        $question = Question::create($data);
        $question->saveOptions(getOrDefault($data['options'], []));
        $question->saveAccepts(getOrDefault($data['accepts'], []));
        return $question;
    }
}
