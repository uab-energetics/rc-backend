<?php

namespace App;

use App\Models\Response;
use Illuminate\Database\Eloquent\Model;

class Encoding extends Model
{
    protected $fillable = ['type', 'publication_id', 'form_id'];

    protected $with = ['simpleResponses'];

    function simpleResponses(){
        return $this->belongsToMany(Response::class, 'encoding_simple_responses', 'encoding_id', 'response_id');
    }
}
