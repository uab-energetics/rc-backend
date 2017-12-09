<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $fillable = ['question_id', 'text', 'number', 'selection', 'bool'];

    function selections(){
        return $this->hasMany(Selections::class, 'response_id');
    }
}
