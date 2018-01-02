<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\EncodingExperimentBranch;
use App\Form;
use App\Models\Question;
use App\Models\Response;
use App\Project;
use App\Publication;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class BranchQuestionMapTest extends JWTTestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
        $this->asAnonymousUser();
    }
    public function testEncodings() {

        $branch = factory(EncodingExperimentBranch::class)->create();
        $questions = [
            factory(Question::class)->create(),
            factory(Question::class)->create(),
            factory(Question::class)->create()
        ];

        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[1]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[1]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[1]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[1]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[0]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[0]->id, []);
        $this->json('POST', "branches/".$branch->id."/questionMap/".$questions[2]->id, []);
        $this->json('DELETE', "branches/".$branch->id."/questionMap/".$questions[1]->id, []);


        $res = $this->json('GET', "branches/".$branch->id."/questionMap");
//        $res->dump();


        /*
        Expect questions[1] will not be there
        Expect questions[0] won't return duplicates
        Expect questions[2] will be included
         */





    }
}
