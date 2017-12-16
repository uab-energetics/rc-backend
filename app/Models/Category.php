<?php

namespace App;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['parent_id', 'name'];

    protected $with = ['children', 'questions'];

    function children(){
        return $this->hasMany(Category::class, 'parent_id');
    }

    function questions(){
        return $this->belongsToMany(Question::class, 'question_in_category', 'category_id', 'question_id');
    }
}
