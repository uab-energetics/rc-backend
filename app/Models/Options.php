<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $fillable = [ 'text' ];

    function question(){
        return $this->belongsTo(Question::class);
    }
}
