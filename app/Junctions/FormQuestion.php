<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormQuestion extends UniqueJunction {
    protected $table = "form_uses_table";

    protected $fillable = [
        'form_id',
        'question_id',
        'scope'
    ];

    /** @return string[] */
    public function uniqueColumns() {
        return ['form_id', 'question_id'];
    }
}
