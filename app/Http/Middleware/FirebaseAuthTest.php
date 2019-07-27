<?php

namespace App\Http\Middleware;

use Tests\TestCase;
use function GuzzleHttp\json_encode;

class FirebaseAuthTest extends TestCase
{
    public function testVerifyFirebaseIdToken()
    {
        $response = $this->post('/secure', [], [
            'Authorization' => '-----'
        ]);

        $response->assertStatus(401);
    }
}
