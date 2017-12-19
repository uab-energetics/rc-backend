<?php

namespace Tests\Unit;

use App\Category;
use App\Encoding;
use App\Form;
use App\FormLayout;
use App\Models\Question;
use App\Models\Response;
use App\Publication;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncodingJoinsTest extends TestCase
{
    use DatabaseTransactions;

    public function testFormJoins() {
        $cat = Category::create([
            'name' => 'Category One'
        ]);
        $question = Question::create([
            'txt' => 'test question'
        ]);
        $cat->questions()->save($question);

        $layout = FormLayout::create([
            'type' => 'simple',
            'root_category_id' => $cat->id
        ]);
        $form = Form::create([
            'layout_id' => $layout->id,
            'name' => "Simple form",
            'desc' => ''
        ]);
        $pub = Publication::create([ 'name' => 'demo pub' ]);


        $encoding = Encoding::create([
            'type' => 'simple',
            'publication_id' => $pub->id,
            'form_id' => $form->id
        ]);
        $response = Response::create([
            'question_id' => $question->id,
            'type' => 'txt'
        ]);
        $encoding->simpleResponses()->save($response);

        echo json_encode(Encoding::find($encoding->id), JSON_PRETTY_PRINT);

        $this->assertTrue(true);
    }

}
