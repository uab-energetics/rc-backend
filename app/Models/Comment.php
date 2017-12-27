<?php

namespace App;

use App\Models\Question;

class Comment extends Model{

    protected $table = "comments";

    protected $fillable = [
        "parent_id",
        "user_id",
        "message"
    ];

}
