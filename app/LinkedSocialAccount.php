<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{

    protected $fillable = ['provider_name', 'provider_id' ];

    function user() {
        return $this->belongsTo(User::class);
    }
}