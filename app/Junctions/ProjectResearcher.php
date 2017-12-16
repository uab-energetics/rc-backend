<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectResearcher extends UniqueJunction {
    protected $table = "project_researcher";

    protected $fillable = ['project_id', 'researcher_id'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'researcher_id'];
    }
}
