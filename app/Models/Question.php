<?php

namespace App\Models;

use App\Form;
use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {


    protected $fillable = ['name', 'prompt', 'default_format', 'description', 'true_option', 'false_option'];
    protected $with = ['options', 'accepts'];
    protected $searchable = self::searchable;

    function options() {
        return $this->hasMany(QuestionOptions::class, 'question_id');
    }

    function responses() {
        return $this->hasMany(Response::class, 'question_id');
    }

    function accepts(){
        return $this->hasMany(AcceptsFormat::class, 'question_id');
    }

    public function forms() {
        return $this->belongsToMany(Form::class, 'form_question', 'question_id', 'form_id');
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

    const searchable = ['name', 'prompt', 'default_format', 'description'];
}
