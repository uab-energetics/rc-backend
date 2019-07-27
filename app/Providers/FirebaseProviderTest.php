<?php

namespace App\Providers;

use Tests\TestCase;
use App\Services\FirebaseService;

class FirebaseProviderTest extends TestCase
{
    public function testResolvingFirebaseService()
    {
        $firebase = app()->make(FirebaseService::class);
        $this->assertNotNull($firebase->firebase);
    }
}
