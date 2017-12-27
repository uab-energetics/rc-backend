<?php

namespace App\Services\Comments;


use App\Comment;

class CommentService {

    static function post($parent_id, $user_id, $message){
        return Comment::create([
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'message' => $message,
        ]);
    }

    static function getThread($root_comment_id){
        return Comment::find($root_comment_id);
    }

    static function edit($comment_id, $message){
        $comment = Comment::find($comment_id);
        $comment->message = $message;
        $comment->save();
        return Comment::find($comment_id);
    }

    static function delete($comment_id){
        $comment = Comment::find($comment_id);
        if(!$comment) return;
        $comment->message = "deleted";
        $comment->save();
    }

}