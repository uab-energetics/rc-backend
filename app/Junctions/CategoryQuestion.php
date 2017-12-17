<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryQuestion extends UniqueJunction {

    protected $table = "category_question";
    protected $fillable = ['question_id', 'category_id'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['question_id', 'category_id'];
    }
}
