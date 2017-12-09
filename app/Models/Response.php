<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $fillable = ['question_id', 'type', 'txt', 'num', 'sel', 'boo'];

    function selections(){
        return $this->hasMany(Selections::class, 'response_id');
    }

    function saveSelections( $str_arr ){
        parent::save();
        $this->selections()->delete();
        $opts = array_map(function($str){
            return [ 'txt' => $str ];
        }, $str_arr );
        $this->selections()->createMany($opts);
    }

    public static function createWithSelections($data){
        $selections = getOrDefault($data['selections'], []);
        $response = Response::create($data);
        $response->saveSelections($selections);
        return $response;
    }
}
