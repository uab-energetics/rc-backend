<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPublication extends UniqueJunction {
    protected $table = "project_publication";

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'publication_id'];
    }
}
