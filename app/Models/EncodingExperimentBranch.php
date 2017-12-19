<?php

namespace App;

use App\Models\Response;
use Illuminate\Database\Eloquent\Model;

class EncodingExperimentBranch extends Model
{
    protected $fillable = ['encoding_id', 'name', 'description'];
    protected $with = ['responses'];

    function responses(){
        return $this->belongsToMany(Response::class, 'branch_responses', 'branch_id', 'response_id');
    }
}
