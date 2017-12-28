<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Comment;
use App\Services\Comments\CommentService;
use Auth;
use Illuminate\Http\Request;

class CommentsController extends Controller {

    function createChannel(Request $request){
        $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'topic' => 'required'
        ]);

        $root_comment = Comment::create([]);
        $channel = Channel::create([
            'name' => $request->input('name'),
            'display_name' => $request->input('display_name'),
            'topic' => $request->input('topic'),
            'root_comment_id' => $root_comment->getKey()
        ]);
        return $channel;
    }

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

    function getChannel($channel_id){
        $channel = Channel::find($channel_id);
        if(!$channel) abort(404);
        return $channel;
    }

    function reply(Request $request, $parent_id){
        $request->validate([
            'message' => 'required'
        ]);
        $user = Auth::user();

        CommentService::createComment(
            $parent_id,
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

}
