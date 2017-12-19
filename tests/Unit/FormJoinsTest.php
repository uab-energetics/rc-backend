<?php

namespace Tests\Unit;

use App\Category;
use App\Form;
use App\FormLayout;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FormJoinsTest extends TestCase
{

    use DatabaseTransactions;

    public function testFormJoins() {
        $cat = Category::create([
            'name' => 'Category One'
        ]);
        $first_child = Category::create([
            'name' => 'First Child'
        ]);
        $first_child->children()->create([
            'name' => 'level three node'
        ]);
        $first_child->questions()->create([
            'txt' => "i'm a leaf node",
            "default_format" => 'txt'
        ]);
        $cat->children()->save($first_child);

        $layout = FormLayout::create([
            'type' => 'simple',
            'root_category_id' => $cat->id
        ]);
        $form = Form::create([
            'layout_id' => $layout->id,
            'name' => "Simple form",
            'desc' => ''
        ]);

        echo json_encode(Form::find($form->id), JSON_PRETTY_PRINT);

        $this->assertTrue(true);
    }

}
