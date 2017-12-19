<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\EncodingExperimentBranch;
use App\Form;
use App\Models\Response;
use App\Project;
use App\Publication;
use App\Rules\Question;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class Encodings extends JWTTestCase
{

    use DatabaseTransactions;

    protected function setUp()
    {
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
    public function testFormBuilder() {

        $form = factory(Form::class)->make();
        $form->rootCategory()->save(factory(Category::class));
        $form->save();
        $publication = factory(Publication::class);



        $create = $this->json("POST", "encodings", [
            'form_id' => $form->getKey(),
            'publication_id' => $publication->getKey()
        ])->assertStatus(200);
        $create_id = $create->json()['id'];

        $get = $this->json('GET', "encodings/$create_id")->assertStatus(200);

        $createBranch = $this->json("POST", "encodings/$create_id/branches", [
            "branch" => factory(EncodingExperimentBranch::class)->make([
                'encoding_id' => $create_id
            ])
        ])->assertStatus(200);
        $branch_id = $createBranch->json()['id'];

        $updateBranch = $this->json("PUT", "branches/$branch_id", [
            'name' => 'Per guest prepare nine peaces of milk with chopped tuna for dessert.'
        ])->assertStatus(200);

        $recordResponse = $this->json("POST", "encodings/$create_id/branches/$branch_id/responses",
            factory(Response::class)->make([
                'question_id' => factory(Question::class)->create()->id
            ]));

        $deleteBranch = $this->json("DELETE", "branches/$branch_id")->assertStatus(200);
    }
}
