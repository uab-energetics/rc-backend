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
            'name' => 'required|unique:channels',
            'display_name' => 'required|string',
            'topic' => 'required|string'
        ]);

        DB::beginTransaction();
            $channel = $this->commentService->makeChannel($request->all());
        DB::commit();

        return $channel;
    }

    function postInChannel(Request $request, $channel_id){
        $request->validate([
            'message' => 'required'
        ]);
        $user = $request->user();

        return $this->commentService->createCommentInChannel(
            $channel_id,
            $user->getKey(),
            $request->input('message')
        );
    }

    function getChannel($channel_name){
        return $this->commentService->findChannel($channel_name);
    }

    function reply(Comment $comment, Request $request){
        $request->validate([
            'message' => 'required'
        ]);
        $user = $request->user();

        $newComment = $this->commentService->createComment(
            $comment->getKey(),
            $user->getKey(),
            $request->message
        );

        return $newComment;
    }

    function edit(Comment $comment, Request $request){
        $request->validate([
            'message' => 'required'
        ]);
        return $this->commentService->editComment(
            $comment->getKey(),
            $request->message
        );
    }

    function delete(Comment $comment, Request $request){
        $this->commentService->deleteComment($comment);
        return okMessage("Comment removed");
    }

    protected $commentService;

    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

}
