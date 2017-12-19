<?php

namespace Tests\Feature\api\users;

use App\Form;
use App\Project;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\JWTTestCase;

class FormBuilder extends JWTTestCase
{

    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        $this->asAnonymousUser();
    }

    public function testFormBuilder() {

        /**
         * -1. create project
         * 0. create form
         * 1. create categories
         * 2. create questions
         * 3. move questions
         * 4. move categories
         * 5. delete question
         * 6. delete category
         * 7. delete form
         * 8. delete project
         */

        // Create project
        $create_project_res = $this->json('POST', "projects", [ 'name' => 'test project' ]);
        $create_project_res->assertStatus(200);
        $project_id = $create_project_res->json()['id'];

        // Get project
        $this->json('GET', "/projects/$project_id")->assertStatus(200);

        // Create form
        $form_res = $this->json('POST', "projects/$project_id/forms", [
            'type' => 'simple',
            'name' => str_random(10),
            'description' => str_random(10) ]);
        $form_res->assertStatus(200);
        $form_id = $form_res->json()['id'];

        // Get form
        $this->json('GET', "/forms/$form_id")->assertStatus(200);

        // Create categories
        $category_res = $this->json('POST', "forms/$form_id/categories", [
            'parent_id' => $form_res->json()['root_category_id'],
            'name' => str_random(10) ]);
        $category_res->assertStatus(200);
        $category_id = $category_res->json()['id'];

        // Get category
        $this->json('GET', "categories/$category_id")->assertStatus(200);

        // Create questions
        $question_res = $this->json('POST', "forms/$form_id/questions", [
            'category_id' => $category_id,
            'question' => [
                'name' => str_random(10),
                'default_format' => 'txt',
                'accepts' => [ 'txt' ],
                'prompt' => 'default question prompt' ]]);
        $question_res->assertStatus(200);
        $question_id = $question_res->json()['id'];

        // get question
        $this->json('GET', "questions/$question_id")->assertStatus(200);

        // other category
        $other_category_id = $this->json('POST', "forms/$form_id/categories", [
            'parent_id' => $form_res->json()['root_category_id'],
            'name' => str_random(10)
        ])->json()['id'];

        // move question
        $this->json('PUT', "forms/$form_id/questions/$question_id", [
            'category_id' => $other_category_id
        ])->assertStatus(200);

        // move category
        $this->json('PUT', "forms/$form_id/categories/$category_id", [
            'parent_id' => $other_category_id
        ])->assertStatus(200);

        // delete everything
        $this->json('DELETE', "categories/$category_id")->assertStatus(200);
        $this->json('DELETE', "questions/$question_id")->assertStatus(200);
        $this->json('DELETE', "forms/$form_id")->assertStatus(200);
        $this->json('DELETE', "projects/$project_id")->assertStatus(200);
    }
}
