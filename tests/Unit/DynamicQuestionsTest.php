<?php

namespace Tests\Unit;

use App\Stores\QuestionStore;
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

//        echo(json_encode(QuestionStore::find($question->id), JSON_PRETTY_PRINT));

        $this->assertTrue(true);
    }
}
