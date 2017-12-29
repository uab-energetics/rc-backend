<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {

    protected $fillable = ['root_comment_id', 'name', 'topic', 'display_name'];

    protected $with = ['rootComment'];

    function rootComment(){
        return $this->belongsTo(Comment::class, 'root_comment_id')->withTrashed();
    }
}
