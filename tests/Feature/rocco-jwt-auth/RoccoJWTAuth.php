<?php

namespace Tests\Feature;

use App\User;
use Firebase\JWT\JWT;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoccoJWTAuth extends TestCase
{
    public function testJWTRoute() {

        // no token
        $this->get('/me')->assertStatus(401);

        $private_key = file_get_contents(__DIR__ . '/rc-auth.pem');

        $user = factory(User::class)->create();

        $token = [
            "iss" => "example.org",
            "aud" => "example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
        ];

        $jwt = JWT::encode($token, $private_key, 'RS256');

        // because there is no user with the correct ID
        $this->get('/me', [
            'Authorization' => "Bearer $jwt"
        ])->assertStatus(500);

        // ** NOTE ** - 'uuid' is a config param. This test would fail if config('custom.jwt_auth.jwt_user_id') !== 'uuid'
        $token['uuid'] = $user->id;
        $jwt = JWT::encode($token, $private_key, 'RS256');

        // now we're good
        $this->get('/me', [
            'Authorization' => "Bearer $jwt"
        ])->assertStatus(200);
    }

}
