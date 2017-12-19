<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class JWTTestCase extends TestCase {

    private $jwt = null;

    function asUser(User $user){
        $this->jwt = JWTAuth::fromUser($user);
    }

    function asAnonymousUser(){
        $this->asUser(factory(User::class)->create());
    }

    public function json($method, $uri, array $data = [], array $headers = []) {
        if($this->jwt) $headers['Authorization'] = 'Bearer ' . $this->jwt;
        return parent::json($method, $uri, $data, $headers);
    }

}
