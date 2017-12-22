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

class EncodingsTest extends JWTTestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
        $this->asAnonymousUser();
    }

    /**
     *  create: ( form_id, publication_id ) -> Encoding
     *  get: ( id ) -> Encoding
     *  createBranch: ( id, Branch ) -> Encoding
     *  updateBranch: ( branch_id, Branch ) -> Branch
     *  deleteBranch: ( branch_id ) -> Encoding
     *  recordResponse: ( id, branch_id, Response ) -> Encoding
     */
    public function testEncodings() {
        /** @var Form $form */

        $form = factory(Form::class)->make();
        $publication = factory(Publication::class)->make();
        $form->save();
        $publication->save();


        $encoding = $this->json("POST", "assignments/manual", [
            'form_id' => $form->getKey(),
            'publication_id' => $publication->getKey(),
            'user_id' => $this->user->getKey(),
        ])->assertStatus(200);
        $encoding_id = $encoding->json()['id'];

        $get = $this->json('GET', "encodings/$encoding_id")->assertStatus(200);

        $branch = $this->json("POST", "encodings/$encoding_id/branches",
            factory(EncodingExperimentBranch::class)->make([
                'encoding_id' => $encoding_id
            ])->toArray()
        )->assertStatus(200)->json();
        $branch_id = $branch['id'];

        $branch['name'] = 'Per guest prepare nine peaces of milk with chopped tuna for dessert.';

        $updateBranch = $this->json("POST", "encodings/$encoding_id/branches", $branch)->assertStatus(200)->json();

        $this->assertEquals($branch, $updateBranch);

        $recordResponse = $this->json("POST", "encodings/$encoding_id/branches/$branch_id/responses",
            factory(Response::class)->make([
                'question_id' => factory(Question::class)->create()->id
            ])->toArray()
        );

        $deleteBranch = $this->json("DELETE", "encodings/$encoding_id/branches/$branch_id")->assertStatus(200);

        $deleteEncoding = $this->json('DELETE', "encodings/$encoding_id")->assertStatus(200);
    }
}
