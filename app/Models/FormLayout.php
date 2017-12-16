<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormLayout extends Model
{
    protected $fillable = ['type', 'root_category_id'];

    protected $with = ['rootCategory'];

    function rootCategory(){
        return $this->belongsTo(Category::class, 'root_category_id');
    }
}
