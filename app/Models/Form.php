<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model {
    protected $fillable = ['root_category_id', 'name', 'desc', 'published'];

    protected $with = ['rootCategory'];

    function rootCategory(){
        return $this->belongsTo(Category::class, 'root_category_id');
    }
}
