<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchResponse extends Model {

    protected $table = 'branch_response';
    protected $fillable = ['branch_id', 'response_id', 'comment'];
}
