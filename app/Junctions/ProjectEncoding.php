<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectEncoding extends UniqueJunction {
    protected $table = "project_encoding";

    protected $fillable = ['project_id', 'encoding_id'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'encoding_id'];
    }
}
