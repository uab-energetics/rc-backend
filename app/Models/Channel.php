<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {

    protected $fillable = ['root_comment_id', 'name', 'topic', 'display_name'];

    protected $with = ['comments'];

    function rootComment(){
        return $this->hasOne(Comment::class, 'root_comment_id');
    }
}
