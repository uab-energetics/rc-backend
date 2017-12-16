<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectForm extends UniqueJunction {
    protected $table = "project_form";

    protected $fillable = ['project_id', 'form_id'];

    /** @return string[] */
    public function uniqueColumns() {
        // TODO: Implement uniqueColumns() method.
    }
}
