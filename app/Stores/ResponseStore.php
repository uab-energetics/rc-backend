<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/8/17
 * Time: 9:57 PM
 */

namespace App\Stores;

use App\Response;
use App\Selections;
use Illuminate\Support\Facades\DB;

class ResponseStore {

    static function create( $data ): Response {
        $res = Response::create($data);
        $res->selections()->saveMany(self::mapSelections(getOrDefault($data['selections'], [])));
        return ResponseStore::find($res->id);
    }

    static function find($id): Response {
        $res = Response::find($id);
        $res->load('selections');
        return $res;
    }

    static function all(): array {
        return Response::with('selections')->get();
    }

    private static function mapSelections($text_arr){
        return array_map(function($sel){
            return new Selections([ 'text' => $sel ]);
        }, $text_arr);
    }

}