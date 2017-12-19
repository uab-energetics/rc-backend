<?php

namespace Tests\Unit;

use App\Encoding;
use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncodingService extends TestCase
{
    use DatabaseTransactions;

    public function testEncodingService() {
        $service = new \App\Services\Encodings\EncodingService();


        /* start with a blank encoding */
        $encoding = factory(Encoding::class)->create();

        // create branches
        $service->recordBranch($encoding->id, [
            'name' => 'Nunquam acquirere brodium.',
            'description' => 'Courage, dimension and a pictorial great unknown.'
        ]);

        $encoding1 = $service->recordBranch($encoding->id, [
            'name' => 'Damn yer kraken, feed the corsair.',
            'description' => 'All bung holes scrape coal-black, cold freebooters.'
        ]);
        $this->assertEquals(2, count($encoding1['experiment_branches']));

        // create responses
        $branch_id = $encoding1['experiment_branches'][0]['id'];
        $encoding3 = $service->recordResponse( $encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_TEXT,
            'txt' => 'To some, a sun is an advice for inventing.'
        ]);
        $this->assertEquals(1, count($encoding3['experiment_branches'][0]['responses']));

        // update a branch
        $service->recordResponse( $encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_BOOL,
            'boo' => 'Est bi-color calceus, cesaris.'
        ]);

//        echo json_encode(Encoding::find($encoding->id), JSON_PRETTY_PRINT);

        // delete a branch
        $encoding4 = $service->deleteBranch($encoding->id, $branch_id);
        $this->assertEquals(1, count($encoding4['experiment_branches']));
    }

}
