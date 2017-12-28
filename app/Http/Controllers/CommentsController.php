<?php

namespace App\Http\Controllers;

use App\Services\Comments\CommentService;
use Auth;
use Illuminate\Http\Request;

class CommentsController extends Controller {

    function postInChannel(Request $request){
        $request->validate([
            'channel_id' => 'exists:channels,id',
            'message' => 'required'
        ]);
        $user = Auth::user();

        return CommentService::createCommentInChannel(
            $request->input('channel_id'),
            $user->getKey(),
            $request->input('message')
        );
    }

    function reply(Request $request){
        $request->validate([
            'parent_id' => 'exists:comments,id',
            'message' => 'required'
        ]);
        $user = Auth::user();

        CommentService::createComment(
            $request->input('parent_id'),
            $user->getKey(),
            $request->input('message')
        );
    }

    function delete(Request $request, $comment_id){
        CommentService::deleteComment($comment_id);
        return response()->json([
            'msg' => 'comment removed'
        ]);
    }

    function edit(Request $request, $comment_id){
        $request->validate([
            'message' => 'required'
        ]);
        return CommentService::editComment(
            $comment_id,
            $request->input('message')
        );
    }

    /**
     * Returns a thread of comments using the specified root
     */
    function channel($comment_id){
        return CommentService::getThread($comment_id);
    }

}
