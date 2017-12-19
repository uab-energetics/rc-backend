<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\Encoding;
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

    public function setUp()
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
    public function testEncodings() {

        $encoding = factory(Encoding::class)->create();
        $branch = factory(EncodingExperimentBranch::class)->create([
            'encoding_id' => $encoding->id
        ]);

        // make a branch
        $this->json('POST', 'encodings/record-branch', [
            'encoding_id' => $encoding->id,
            'branch' => $branch
        ])->assertStatus(200);

        // make another branch
        $new_branch = $this->json('POST', 'encodings/record-branch', [
            'encoding_id' => $encoding->id,
            'branch' => factory(EncodingExperimentBranch::class)->make([
                'encoding_id' => $encoding->id
            ])
        ])->assertStatus(200)->json();

        // record a response
        $this->json('POST', 'encodings/record-response', [
            'encoding_id' => $encoding->id,
            'branch_id' => $new_branch['id'],
            'response' => factory(Response::class)->make()
        ])->assertStatus(200);

        // delete a branch
        $this->json('POST', 'encodings/delete-branch', [
            'encoding_id' => $encoding->id,
            'branch_id' => $branch->id
        ])->assertStatus(200);

        echo json_encode(Encoding::find($encoding->id), JSON_PRETTY_PRINT);
    }
}
