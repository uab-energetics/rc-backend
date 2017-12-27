<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comments";

    protected $fillable = [
        "parent_id",
        "user_id",
        "message"
    ];

    function children(){
        // TODO
    }

    function childrenRecursive(){
        // TODO
    }
}
