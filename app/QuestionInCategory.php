<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionInCategory extends Model
{
    protected $table = "question_in_category";
    protected $fillable = ['question_id', 'category_id'];
}
