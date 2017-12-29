<?php

namespace App\Services\Comments;


use App\Channel;
use App\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService {

    public function makeChannel($params) {
        $root_comment = Comment::create([]);
        $params['root_comment_id'] = $root_comment->getKey();
        $channel = Channel::create($params);
        return $channel;
    }

    public function createCommentInChannel($channel_id, $user_id, $message){
        $channel = Channel::findOrFail($channel_id);
        return $this->createComment($channel->root_comment_id, $user_id, $message);
    }

    public function createComment($parent_id, $user_id, $message){
        return Comment::create([
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'message' => $message
        ]);
    }

    public function editComment($comment_id, $message){
        $comment = Comment::findOrFail($comment_id);
        $comment->message = $message;
        $comment->save();
        return $comment->refresh();
    }

    public function deleteComment(Comment $comment){
        $comment->update(['message' => 'deleted']);
        $comment->delete();
        return true;
    }

    public function findChannel($channel_name) {
        $channel = Channel::where('name', '=', $channel_name)->first();
        if ($channel === null) throw (new ModelNotFoundException())->setModel(Channel::class);
        return $channel;
    }

}