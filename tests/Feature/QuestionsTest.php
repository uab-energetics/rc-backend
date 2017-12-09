<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostQuestion() {
        $response = $this->postJson('/api/questions', [
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
