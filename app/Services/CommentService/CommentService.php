<?php

namespace App\Services\Comments;


use App\Channel;
use App\Comment;

class CommentService {

    static function createCommentInChannel($channel_id, $user_id, $message){
        $channel = Channel::find($channel_id);
        return CommentService::createComment($channel->root_comment_id, $user_id, $message);
    }

    static function createComment($parent_id, $user_id, $message){
        return Comment::create([
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'message' => $message
        ]);
    }

    static function editComment($comment_id, $message){
        $comment = Comment::find($comment_id);
        $comment->message = $message;
        $comment->save();
        return Comment::find($comment_id);
    }

    static function deleteComment($comment_id){
        $comment = Comment::find($comment_id);
        if(!$comment) return;
        $comment->message = "deleted";
        $comment->save();
    }

}