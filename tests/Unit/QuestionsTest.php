<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionsTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateQuestion() {
        $q = Question::createWithRel([
            'txt' => 'Demo Question',
            'options' => [
                'A', 'B', 'C'
            ],
            'accepts' => [ 'txt', 'sel', 'multi-sel' ]
        ]);

        Response::createWithSelections([
            'question_id' => $q->id,
            'selections' => [ 'B', 'C' ],
            'type' => 'multi-sel'
        ]);

//        echo json_encode( Question::with('responses')->get()->toArray(), JSON_PRETTY_PRINT);

        $this->assertEquals(1, $q->responses()->count());
        $this->assertEquals(3, $q->accepts()->count());
        $this->assertEquals(3, $q->options()->count());
    }

}
