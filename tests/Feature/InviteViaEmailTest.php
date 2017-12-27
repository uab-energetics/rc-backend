<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class InviteViaEmailTest extends JWTTestCase
{
    use DatabaseTransactions;


    public function testInviteViaEmail() {
        $this->asAnonymousUser();

        $project = factory(Project::class)->create()->id;
        $callback = 'http://v3.researchcoder.com';
        $to_email = 'chris.rocco7@gmail.com';

        $response = $this->json('POST', '/invite-to-project', [
            'project_id' => $project,
            'to_email' => $to_email,
            'callback_url' => $callback,
            'callback_params' => [
                'action' => 'redeem_invite'
            ]
        ]);

        $response->dump();
    }

}