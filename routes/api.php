<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConflictsController;
use App\Http\Controllers\EncodingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;



/**
 * To hell with the facades, I need my intelli-sense.
 *
 * @var $route \Illuminate\Routing\Router
 */
$route = resolve(\Illuminate\Routing\Router::class);



$route->group(['prefix' => 'auth'], function () use ($route) {
    $route->post('login', AuthController::class."@login");
    $route->post('register', AuthController::class."@register");
});


$route->group(['middleware' => 'jwt.auth'], function () use ($route) {

    /**
     *  =================================
     *      USERS
     *  =================================
     *
     *  Routes dealing with user management
     *
     */
    $route->put('/my-profile', UserController::class."@updateProfile");
    $route->group(['prefix' => 'users'], function() use ($route) {
        $route->get('/', UserController::class."@search");
        $route->group(['prefix' => 'projects'], function() use ($route) {
            $route->get('/', UserController::class."@retrieveResearcherProjects");
            $route->get('/coder', UserController::class."@retrieveCoderProjects");
            $route->get('/researcher', UserController::class."@retrieveResearcherProjects");
        });

        $route->get('/encodings', UserController::class."@retrieveEncodings");

    });

    /**
     *  ========================================================
     *  PUBLICATIONS
     *  ========================================================
     */
    
    $route->group(['prefix' => 'publications'], function () use ($route) {
        $route->post('/', PublicationController::class."@create");
        $route->get('/', PublicationController::class."@search");
        $route->get('/{publication}', PublicationController::class."@retrieve");
        $route->put('/{publication}', PublicationController::class."@update");
        $route->delete('/{publication}', PublicationController::class."@delete");
    });

    /**
     * ===============================================
     * PROJECTS
     * ===============================================
     */
    $route->group(['prefix' => 'projects'], function () use ($route) {
        $route->post('/', ProjectController::class."@create");
        $route->get('/', ProjectController::class."@search");
        $route->get('/{project}', ProjectController::class."@retrieve");
        $route->put('/{project}', ProjectController::class."@update");
        $route->delete('/{project}', ProjectController::class."@delete");

        $route->group(['prefix' => '{project}'], function () use ($route) {
            $route->get('/forms', ProjectController::class.'@retrieveForms');
            $route->post('/forms', FormController::class."@create");

            $route->get('/publications', ProjectController::class."@retrievePublications");

            $route->group(['prefix' => 'publications'], function () use ($route) {
                $route->post('/', PublicationController::class."@createInProject");
                $route->post('/{publication}', ProjectController::class."@addPublication");
                $route->delete('/{publication}', ProjectController::class."@removePublication");
            });

            $route->get('/researchers', ProjectController::class."@getResearchers");
            $route->post('/invite-researcher', ProjectController::class."@inviteResearcher");
        });
    });

    /**
     *  ================================
     *  FORMS
     *  ================================
     */
    $route->group(['prefix' => 'forms'], function () use ($route) {
        $route->get('{form}', FormController::class."@retrieve");
        $route->get('/', FormController::class."@search");
        $route->put('/{form}', FormController::class."@update");
        $route->delete('{form}', FormController::class."@delete");

        $route->get('/{form}/export', FormController::class."@export");

        $route->group(['prefix' => '{form}/questions'], function() use ($route) {
            $route->post('/', QuestionController::class."@createQuestion");
            $route->post('{question}', FormController::class."@addQuestion");
            $route->put('{question}', FormController::class."@moveQuestion");
            $route->delete('{question}', FormController::class."@removeQuestion");
        });

        $route->group(['prefix' => '{form}/categories'], function () use ($route) {
            $route->post('/', CategoryController::class."@create");
            $route->put('/{category}', CategoryController::class."@update");
            $route->delete('/{category}', CategoryController::class."@delete");
        });
    });

    /**
     * =================================
     * QUESTIONS
     * =================================
     */
    $route->group(['prefix' => 'questions'], function () use ($route) {
        $route->post('/', QuestionController::class."@create");
        $route->get('/{question}', QuestionController::class."@retrieve");
        $route->get('/', QuestionController::class."@search");
        $route->put('/{question}', QuestionController::class."@update");
        $route->delete('/{question}', QuestionController::class."@delete");
    });

    $route->group(['prefix' => 'categories'], function () use ($route) {
        $route->get('/{category}', CategoryController::class."@retrieve");
    });

    /**
     *  ===========================================
     *  ENCODINGS
     *  ===========================================
     */
    $route->group(['prefix' => 'encodings'], function () use ($route) {
        $route->get('/{encoding}', EncodingController::class."@retrieve");
        $route->put('/{encoding}', EncodingController::class."@update");
        $route->delete('/{encoding}', EncodingController::class."@delete");
        $route->group(['prefix' => '{encoding}/branches'], function () use ($route) {
            $route->post('/', EncodingController::class."@createBranch");
            $route->delete('/{branch}', EncodingController::class."@deleteBranch");
            $route->post('/{branch}/responses', EncodingController::class."@createBranchResponse");
        });
        $route->post('/{encoding}/responses', EncodingController::class."@createSimpleResponse");
    });
    $route->get('conflict-report/{encoding_id}', ConflictsController::class."@getConflictsReport");

    $route->group(['prefix' => 'assignments'], function () use ($route) {
        $route->post('/manual', AssignmentController::class."@assignOne");
    });

    $route->group(['prefix' => 'responses'], function () use ($route) {

    });


    /**
     * ==========================================
     *  NOTIFICATIONS
     * ==========================================
     */

    $route->get('/notifications', NotificationsController::class."@unreadNotifications");
    $route->get('/notifications/mark-read', NotificationsController::class."@markAllRead");

});