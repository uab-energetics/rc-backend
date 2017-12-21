<?php

namespace Tests\Feature\api\users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileTest extends JWTTestCase {

    use DatabaseTransactions;

    public function testUpdateProfile() {

        $user = factory(User::class)->create([
            'name' => 'Chris Rocco',
            'image' => '123'
        ]);

        $this->asUser($user);

        $this->json('PUT', '/my-profile', [
            'name' => 'Caleb Falcione',
            'image' => 'abc'
        ])->assertStatus(200)
            ->assertJson([
                'name' => 'Caleb Falcione',
                'image' => 'abc'
            ]);

    }
}
