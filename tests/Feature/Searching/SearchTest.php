<?php


namespace Tests\Feature\Searching;


use App\Services\Forms\FormService;
use App\Services\Projects\ProjectService;
use App\Services\Publications\PublicationService;
use App\Services\Questions\QuestionService;
use App\Services\Users\UserService;
use Tests\TestCase;

class SearchTest extends TestCase {

    public function testProjectSearch() {
        $this->projectService->search("test");
        $this->assertTrue(true);
    }

    public function testFormSearch() {
        $this->formService->search("test");
        $this->assertTrue(true);
    }

    public function testUserSearch() {
        $this->userService->search("test");
        $this->assertTrue(true);
   }

    public function testPublicationSearch() {
        $this->publicationService->search("test");
        $this->assertTrue(true);
   }

   public function testQuestionSearch() {
        $this->questionService->search("test");
        $this->assertTrue(true);
   }

    public function setUp() {
        parent::setUp();
        $this->projectService =      app()->make(ProjectService::class);
        $this->formService =         app()->make(FormService::class);
        $this->userService =         app()->make(UserService::class);
        $this->publicationService =  app()->make(PublicationService::class);
        $this->questionService =     app()->make(QuestionService::class);
    }


    /** @var ProjectService */
    protected $projectService;
    /** @var FormService */
    protected $formService;
    /** @var UserService */
    protected $userService;
    /** @var PublicationService */
    protected $publicationService;
    /** @var QuestionService */
    protected $questionService;

}