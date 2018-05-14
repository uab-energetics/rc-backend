<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller {

    function unreadNotifications(Request $request){
        return $request->user()->unreadNotifications;
    }

    function markAllRead(Request $request){
        $request->user()->unreadNotifications->markAsRead();
        return response()->json([
            'status' => 'ok'
        ], 200);
    }

}
