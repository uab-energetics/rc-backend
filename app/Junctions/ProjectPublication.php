<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPublication extends UniqueJunction {
    protected $table = "project_publication";

    protected $fillable = [
        'project_id',
        'publication_id',
    ];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'publication_id'];
    }
}
