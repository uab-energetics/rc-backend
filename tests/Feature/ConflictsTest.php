<?php

namespace Tests\Feature\api\users;

use App\Category;
use App\EncodingExperimentBranch;
use App\Form;
use App\Models\Question;
use App\Models\Response;
use App\Project;
use App\Publication;
use App\Rules\QuestionRule;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class ConflictsTest extends JWTTestCase
{
    use DatabaseTransactions;

    public function testConflictDetection() {

    }
}



$questions = [
    factory(Question::class)->create([
        'id' => 1,
        'name' => 'Question One',
    ]),
    factory(Question::class)->create([
        'id' => 1,
        'name' => 'Question One',
    ]),
    factory(Question::class)->create([
        'id' => 1,
        'name' => 'Question One',
    ])
];


$encodings = [
    factory(EncodingExperimentBranch::class)->create()
];