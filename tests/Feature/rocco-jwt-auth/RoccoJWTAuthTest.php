<?php

namespace Tests\Feature;

use App\User;
use Firebase\JWT\JWT;
use Tests\TestCase;

class RoccoJWTAuthTest extends TestCase
{
    public function testJWTRoute() {

        config([
            'rocco-jwt-auth.public_key' => file_get_contents(__DIR__ . '/rc-auth.pub' ),
            'user_id_field' => 'id',
            'jwt_user_id' => 'uuid',
        ]);

        // TEST SECURED ROUTE WITH NO TOKEN PROVIDED
        $this->get('/me')->assertStatus(401);

        // CREATE A VALID JWT
        $private_key = file_get_contents(__DIR__ . '/rc-auth.pem');
        $user = factory(User::class)->create();
        $token = [
            "iss" => "example.org",
            "aud" => "example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
        ];
        $jwt = JWT::encode($token, $private_key, 'RS256');

        // EXPECT A 500 BEFORE THE USER IS IN THE DATABASE
        $res = $this->get('/me', [
            'Authorization' => "Bearer $jwt"
        ])->assertStatus(400)->assertJsonFragment(['status' => 'USER_ID_NOT_FOUND']);


        // set the user in the jwt
        $token['user']['_id'] = $user->uuid;
        $jwt = JWT::encode($token, $private_key, 'RS256');

        // SEND A REQUEST WITH A VALID JWT
        $response = $this->get('/me', [
            'Authorization' => "Bearer $jwt"
        ]);

        $response->assertStatus(200);

    }

}
