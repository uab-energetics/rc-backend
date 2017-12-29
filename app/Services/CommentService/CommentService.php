<?php

namespace App\Services\Comments;


use App\Channel;
use App\Comment;

class CommentService {

    public function createCommentInChannel($channel_id, $user_id, $message){
        $channel = Channel::findOrFail($channel_id);
        return CommentService::createComment($channel->root_comment_id, $user_id, $message);
    }

    public function createComment($parent_id, $user_id, $message){
        return Comment::create([
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'message' => $message
        ]);
    }

    public function editComment($comment_id, $message){
        $comment = Comment::find($comment_id);
        $comment->message = $message;
        $comment->save();
        return Comment::find($comment_id);
    }

    public function deleteComment($comment_id){
        $comment = Comment::find($comment_id);
        if(!$comment) return;
        $comment->message = "deleted";
        $comment->save();
    }

}