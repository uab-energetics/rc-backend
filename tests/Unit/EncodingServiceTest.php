<?php

namespace Tests\Unit;

use App\Encoding;
use App\Models\Question;
use App\Services\Encodings\EncodingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncodingServiceTest extends TestCase {
    use DatabaseTransactions;

    public function testEncodingService() {
        $service = $this->encodingService;


        /* start with a blank encoding */
        $encoding = factory(Encoding::class)->create();

        // create branches
        $service->recordBranch($encoding->id, [
            'name' => 'Nunquam acquirere brodium.',
            'description' => 'Courage, dimension and a pictorial great unknown.'
        ]);

        $branch1 = $service->recordBranch($encoding->id, [
            'name' => 'Damn yer kraken, feed the corsair.',
            'description' => 'All bung holes scrape coal-black, cold freebooters.'
        ]);

        // create responses
        $branch_id = $branch1['id'];
        $encoding3 = $service->recordResponse($encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_TEXT,
            RESPONSE_TEXT => 'To some, a sun is an advice for inventing.'
        ]);
        $this->assertEquals(1, count($encoding3['experiment_branches'][1]['responses']));

        // update a branch
        $service->recordResponse($encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_BOOL,
            RESPONSE_BOOL => 'Est bi-color calceus, cesaris.'
        ]);

//        echo json_encode(Encoding::find($encoding->id), JSON_PRETTY_PRINT);

        // delete a branch
        $encoding4 = $service->deleteBranch($encoding->id, $branch_id);
        $this->assertEquals(1, count($encoding4['experiment_branches']));
    }

}
