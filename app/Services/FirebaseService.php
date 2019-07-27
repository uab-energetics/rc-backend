<?php

namespace App\Services;

use Kreait\Firebase\ServiceAccount;

class FirebaseService
{

    public $firebase;

    function __construct()
    {
        $credentialsFile = env('GOOGLE_APPLICATION_CREDENTIALS');
        $serviceAccount = ServiceAccount::fromJsonFile($credentialsFile);
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
    }
}
