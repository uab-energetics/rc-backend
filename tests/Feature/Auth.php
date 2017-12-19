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

    public function testAuthFlow() {
        // register
        // login

        $email = 'chris.rocco7@gmail.com';
        $password = 'password';

        $expectedResponse = [
            'user' => [
                'name',
                'email',
                'image'
            ],
            'token'
        ];

        $this->json("POST", "auth/register", [
            'name' => 'Chris Rocco',
            'email' => $email,
            'password' => $password
        ])->assertStatus(200)
            ->assertJsonStructure($expectedResponse);

        $this->json("POST", "auth/login", [
            'email' => $email,
            'password' => $password
        ])->assertStatus(200)
            ->assertJsonStructure($expectedResponse);
    }
}
