<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selections extends Model {
    protected $fillable = ['response_id', 'option_id', 'text'];
}
