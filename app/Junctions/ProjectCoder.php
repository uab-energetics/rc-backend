<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCoder extends UniqueJunction {
    protected $table = "project_coder";

    protected $fillable = ['project_id', 'coder_id'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'coder_id'];
    }
}
