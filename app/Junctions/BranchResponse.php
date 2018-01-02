<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchResponse extends UniqueJunction {

    protected $table = 'branch_responses';
    protected $fillable = ['branch_id', 'response_id', 'comment'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['branch_id', 'response_id'];
    }
}
