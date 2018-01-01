<?php

namespace App;

use App\Models\Response;
use Illuminate\Database\Eloquent\Model;

class EncodingExperimentBranch extends Model {
    protected $table = 'encoding_experiment_branches';
    protected $fillable = ['encoding_id', 'name', 'description', 'index'];
    protected $with = ['responses'];

    function responses() {
        return $this->belongsToMany(Response::class, 'branch_responses', 'branch_id', 'response_id');
    }

    function encoding() {
        return $this->belongsTo(Encoding::class, 'encoding_id');
    }
}
