<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\Response;
use App\Services\Questions\QuestionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateQuestion() {
        $q = $this->questionService->makeQuestion([
            'name' => "Test Question",
            'prompt' => 'What is a question?',
            'default_format' => 'sel',
            'options' => [
                ['txt' => 'A']
            ],
            'accepts' => [
                ['type' => 'sel'],
                ['type' => 'multi-sel'],
            ]
        ]);

        Response::createWithSelections([
            'question_id' => $q->id,
            'selections' => [
                ['txt' => 'B'],
                ['txt' => 'C']
            ],
            'type' => 'multi-sel'
        ]);

//        echo json_encode( Question::with('responses')->get()->toArray(), JSON_PRETTY_PRINT);

        $this->assertEquals(1, $q->responses()->count());
        $this->assertEquals(1, $q->options()->count());
        $this->assertEquals(3, $q->accepts()->count());
    }

}
