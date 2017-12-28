<?php

namespace Tests\Feature;


use App\Comment;
use App\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class GetterTest extends JWTTestCase
{
    use DatabaseTransactions;

    function testSimpleGetter(){
        $this->asAnonymousUser();

        $project_id = factory(Project::class)->create()->id;

        $res = $this->json('GET', "projects/$project_id");

        $res->dump();
    }
}