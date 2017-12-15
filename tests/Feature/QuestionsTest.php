<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionsTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostQuestion() {
        $response = $this->postJson('/questions', [
            'accepts' => [
                ['type' => 'txt'],
                ['type' => 'boo']
            ],
            'txt' => "Simple Question",
            'default_format' => 'txt'
        ]);

        $response->assertStatus(200);

//        echo json_encode(Question::all(), JSON_PRETTY_PRINT);
    }
}
