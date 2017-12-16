<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model {
    protected $fillable = ['layout_id', 'name', 'desc', 'published'];

    protected $with = ['layout'];

    function layout(){
        return $this->belongsTo(FormLayout::class, 'layout_id');
    }
}
