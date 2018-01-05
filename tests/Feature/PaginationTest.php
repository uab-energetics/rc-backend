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

class PaginationTest extends JWTTestCase {

    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
        $this->asAnonymousUser();
    }
    public function testPagination() {

        $res = $this->json('GET', '/publications', [
            'page' => 1,
            'search' => 'Bypass',
            'results_per_page' => 35
        ]);

        $res_basic = $this->json('GET', '/publications');

//        echo json_encode($res->json(), JSON_PRETTY_PRINT);
//        echo json_encode($res_basic->json(), JSON_PRETTY_PRINT);
    }
}
