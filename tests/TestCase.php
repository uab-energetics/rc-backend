<?php

namespace Tests;

use App\Services\Encodings\EncodingService;
use App\Services\Forms\CategoryService;
use App\Services\Forms\FormService;
use App\Services\Projects\ProjectService;
use App\Services\Questions\QuestionService;
use App\Services\Users\UserService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    use CreatesApplication;

    /** @var QuestionService */
    protected $questionService;
    /** @var FormService */
    protected $formService;
    /** @var CategoryService */
    protected $categoryService;
    /** @var ProjectService */
    protected $projectService;
    /** @var UserService */
    protected $userService;
    /** @var EncodingService */
    protected $encodingService;


    public function setUp() {
        parent::setUp();
        $this->questionService = $this->app->make(QuestionService::class);
        $this->formService = $this->app->make(FormService::class);
        $this->categoryService = $this->app->make(CategoryService::class);
        $this->projectService = $this->app->make(ProjectService::class);
        $this->userService = $this->app->make(UserService::class);
        $this->encodingService = $this->app->make(EncodingService::class);
    }
}
