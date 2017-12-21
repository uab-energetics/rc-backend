<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model {

    protected $table = 'question_options';
    protected $fillable = ['txt'];
    public $timestamps = false;

    function question() {
        return $this->belongsTo(Question::class);
    }
}
