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

    function postInChannel(Request $request, $channel_id){
        $request->validate([
            'message' => 'required'
        ]);
        $user = Auth::user();

        return $this->commentService->createCommentInChannel(
            $channel_id,
            $user->getKey(),
            $request->input('message')
        );
    }

    function getChannel($channel_name){
        $channel = Channel::where('name', '=', $channel_name)->first();
        if(!$channel) abort(404);
        return $channel;
    }

    function reply(Request $request, $parent_id){
        $request->validate([
            'message' => 'required'
        ]);
        $user = Auth::user();

        $this->commentService->createComment(
            $parent_id,
            $user->getKey(),
            $request->input('message')
        );
    }

    function delete(Request $request, $comment_id){
        $this->commentService->deleteComment($comment_id);
        return response()->json([
            'msg' => 'comment removed'
        ]);
    }

    function edit(Request $request, $comment_id){
        $request->validate([
            'message' => 'required'
        ]);
        return $this->commentService->editComment(
            $comment_id,
            $request->input('message')
        );
    }

    /** @var CommentService  */
    protected $commentService;

    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

}
