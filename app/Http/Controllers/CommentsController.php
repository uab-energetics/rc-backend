<?php

namespace App\Http\Controllers;

use App\Services\Comments\CommentService;
use Auth;
use Illuminate\Http\Request;

class CommentsController extends Controller {

    function post(Request $request){
        $request->validate([
            'parent_id' => 'exists:comments,id',
            'message' => 'required'
        ]);
        $user = Auth::user();

        return CommentService::post($request->parent_id, $user->getKey(), $request->message);
    }

    function delete(Request $request, $comment_id){
        CommentService::delete($comment_id);
        return response()->json([
            'msg' => 'comment removed'
        ]);
    }

    function edit(Request $request, $comment_id){
        $request->validate([
            'message' => 'required'
        ]);

        return CommentService::edit($comment_id, $request->message);
    }

    /**
     * Returns a thread of comments using the specified root
     */
    function thread($comment_id){
        return CommentService::getThread($comment_id);
    }

}
