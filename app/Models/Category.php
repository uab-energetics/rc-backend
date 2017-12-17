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

    function parent() {
        return $this->hasOne(Category::class, 'parent_id');
    }

    function questions(){
        return $this->belongsToMany(Question::class, 'form_question', 'category_id', 'question_id');
    }

    function form() {
        return $this->hasOne(Form::class, 'root_category_id');
    }

    public function getForm() {
        $form = $this->form()->first();
        if ($form !== null) {
            return $form;
        }
        return $this->parent()->first()->getForm();
    }
}
