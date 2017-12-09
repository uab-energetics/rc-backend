<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [ 'text' ];

    function options() {
        return $this->hasMany(Options::class, 'question_id');
    }
}
