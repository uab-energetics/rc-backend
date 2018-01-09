<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\EncodingExperimentBranch;
use App\Form;
use App\Models\Question;
use App\Models\Response;
use App\Project;
use App\Publication;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class ErrorReportingTest extends JWTTestCase {

    use DatabaseTransactions;


    /**
     * Calls a route that intentionally throws an error, expecting it to be intercepted by Sentry
     */
    public function testEncodings() {
        $this->asAnonymousUser();

        $response = $this->json('GET', '/error-reporting-test');

        $response->dump();
    }
}
