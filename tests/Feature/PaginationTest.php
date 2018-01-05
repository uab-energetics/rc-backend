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

        factory(Publication::class, 50)->create();

        $res = $this->json('GET', '/publications', [
            'page' => 1,
            'page_size' => 45
        ]);

        $this->assertEquals(count($res->json()['data']), 45);

        factory(Publication::class, 4)->create([ 'name' => 'test paper name' ]);

        $search_response = $this->json('GET', '/publications', [
            'page' => 0,
            'search' => 'test paper name',
            'page_size' => 45
        ]);

        $this->assertEquals(count($search_response->json()['data']), 4);
    }
}
