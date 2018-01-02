<?php

namespace Tests\Unit;

use App\Encoding;
use App\EncodingExperimentBranch;
use App\Models\Question;
use App\Services\Encodings\EncodingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BranchQuestionsTest extends TestCase {
    use DatabaseTransactions;

    public function testQuestionMap() {

        $branch = factory(EncodingExperimentBranch::class)->create();

        $questions = [
            factory(Question::class)->create()->id,
            factory(Question::class)->create()->id,
            factory(Question::class)->create()->id
        ];

        $branch->questionMap()->syncWithoutDetaching($questions);
        $branch->questionMap()->syncWithoutDetaching($questions);

        echo json_encode($branch->questionMap, JSON_PRETTY_PRINT);

    }

}
