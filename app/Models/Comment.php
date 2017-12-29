<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = "comments";
    protected $with = ['children', 'user'];

    protected $fillable = [
        "parent_id",
        "user_id",
        "message"
    ];

    function children(){
        return $this->hasMany(Comment::class, 'parent_id')->withTrashed();
    }

    function user(){
        return $this->belongsTo(User::class, "user_id");
    }

}
