<?php

namespace App;

use App\Models\Response;
use Illuminate\Database\Eloquent\Model;

class Encoding extends Model
{
    protected $fillable = ['type', 'publication_id', 'form_id', 'owner_id'];

    protected $with = ['simpleResponses', 'experimentBranches'];

    function publication() {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    function form() {
        return $this->belongsTo(Form::class, 'form_id');
    }

    function simpleResponses(){
        return $this->belongsToMany(Response::class, 'encoding_simple_responses', 'encoding_id', 'response_id');
    }

    function experimentBranches(){
        return $this->hasMany(EncodingExperimentBranch::class, 'encoding_id');
    }
}
