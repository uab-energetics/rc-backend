<?php

namespace Tests\Feature;


use App\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class CommentsTest extends JWTTestCase
{
    use DatabaseTransactions;

    function testsComments(){
        $this->asAnonymousUser();

        $thread_root = factory(Comment::class)->create([
            'user_id' => null,
            'message' => null
        ]);

        // 1. create a comment
        // 2. edit it
        // 3. reply to it
        // 4. delete the first comment
        // 5. get the whole thread

        $res_create = $this->json("POST", '/comments', [
            'parent_id' => $thread_root->getKey(),
            'message' => 'You dumb-ass! It says it right in the paper on line 22'
        ]);
        $comment_id = $res_create->json()['id'];

        $res_edit = $this->json("PUT", "/comments/$comment_id", [
            'message' => "Oh.. it was actually referring to a different study branch. Nevermind then."
        ]);


        factory(Comment::class)->create([
            'parent_id' => $comment_id,
            'message' => "I saw you call him a dumb-ass before editing your comment!"
        ]);

        factory(Comment::class)->create([
            'parent_id' => $comment_id,
            'message' => "Double-check yourself before calling names"
        ]);

        $res_delete = $this->json("DELETE", "/comments/$comment_id");
        $res_thread = $this->json("GET", "/comments/".$thread_root->id);

//        $res_create->dump();
//        $res_edit->dump();
//        $res_delete->dump();
        $res_thread->dump();
    }
}