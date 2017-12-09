<?php

namespace Tests\Unit;

use App\Stores\QuestionStore;
use App\Stores\ResponseStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicQuestionsTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBuilder() {
        $question = QuestionStore::create([
            'text' => 'some sample text',
            'options' => [
                'one',
                'two',
                'three'
            ]
        ]);

        QuestionStore::update($question->id, [
            'options' => ['changed option']
        ]);

        $response = ResponseStore::create([
            'question_id' => $question->id,
            'text_val' => 'My text response',
            'selections' => ['a', 'b']
        ]);

        echo json_encode($response, JSON_PRETTY_PRINT);
        echo json_encode(QuestionStore::find($question->id), JSON_PRETTY_PRINT);

        $this->assertTrue(true);
    }

}
