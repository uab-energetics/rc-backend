<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    protected $fillable = ['prompt', 'description', 'true_option', 'false_option'];
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

    public function saveOptions( $opts_arr ){
        parent::save();                         // make sure the question exists in DB
        $this->options()->delete();             // delete any existing options
        $this->options()->createMany($opts_arr);    // create with FK references
    }

    public function saveAccepts( $accepts_arr ){
        parent::save();
        $this->accepts()->delete();
        $this->accepts()->createMany($accepts_arr);
    }

    /**
     * @param $data
     * @return Question
     */
    public static function createWithRel($data){
        $question = Question::create($data);
        $question->saveOptions(getOrDefault($data['options'], []));
        $question->saveAccepts(getOrDefault($data['accepts'], []));
        return $question;
    }
}
