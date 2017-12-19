<?php

namespace Tests\Unit;

use App\Category;
use App\Encoding;
use App\Form;
use App\FormLayout;
use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncodingService extends TestCase
{
    use DatabaseTransactions;

    public function testEncodingService() {
        $service = new \App\Services\Encodings\EncodingService();

        // 1. need a form
        // 2. need an encoding

        $encoding = factory(Encoding::class)->create();

        // create branches
        $service->createBranch($encoding->id, [
            'name' => 'Nunquam acquirere brodium.',
            'desc' => 'Courage, dimension and a pictorial great unknown.'
        ]);

        $encoding1 = $service->createBranch($encoding->id, [
            'name' => 'Damn yer kraken, feed the corsair.',
            'desc' => 'All bung holes scrape coal-black, cold freebooters.'
        ]);

        // create responses
        $branch_id = $encoding1['experiment_branches'][0]['id'];
        $service->recordResponse( $encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_TEXT,
            'txt' => 'To some, a sun is an advice for inventing.'
        ]);
        $encoding3 = $service->recordResponse( $encoding->id, $branch_id, [
            'question_id' => factory(Question::class)->create()->id,
            'type' => RESPONSE_BOOL,
            'boo' => 'Est bi-color calceus, cesaris.'
        ]);

        echo json_encode($encoding3, JSON_PRETTY_PRINT);

        $service->deleteBranch($encoding->id, $branch_id);
    }

}
