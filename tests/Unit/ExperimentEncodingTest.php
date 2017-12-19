<?php

namespace Tests\Unit;

use App\Category;
use App\Encoding;
use App\EncodingExperimentBranch;
use App\Form;
use App\FormLayout;
use App\Models\Question;
use App\Models\Response;
use App\Publication;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperimentEncodingTest extends TestCase {

    use DatabaseTransactions;

    public function testExperiment() {
//        $cat = Category::create([
//            'name' => 'Category One'
//        ]);
//        $question = Question::create([
//            'txt' => 'test question'
//        ]);
//        $cat->questions()->save($question);
//
//        $layout = FormLayout::create([
//            'type' => 'simple',
//            'root_category_id' => $cat->id
//        ]);
//        $form = Form::create([
//            'layout_id' => $layout->id,
//            'name' => "Simple form",
//            'desc' => ''
//        ]);
//        $pub = Publication::create([ 'name' => 'demo pub' ]);
//
//
//        $encoding = Encoding::create([
//            'type' => 'experiment',
//            'publication_id' => $pub->id,
//            'form_id' => $form->id
//        ]);
//        $branch = EncodingExperimentBranch::create([
//            'encoding_id' => $encoding->id,
//            'name' => 'Simple Branch'
//        ]);
//        $response = Response::create([
//            'question_id' => $question->id,
//            'type' => 'txt'
//        ]);
//        $branch->responses()->save($response);
//
//        echo json_encode(Encoding::find($encoding->id), JSON_PRETTY_PRINT);

        $this->assertTrue(true);
    }

}
