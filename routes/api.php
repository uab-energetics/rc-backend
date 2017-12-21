<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EncodingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', AuthController::class."@login");
    Route::post('register', AuthController::class."@register");
});


Route::group(['middleware' => 'jwt.auth'], function () {

    /**
     * USERS
     * ===============
     */
    Route::put('/my-profile', UserController::class."@updateProfile");
    Route::group(['prefix' => 'users'], function() {
        Route::get('/', UserController::class."@search");
        Route::group(['prefix' => 'projects'], function() {
            Route::get('/', UserController::class."@retrieveResearcherProjects");
            Route::get('/coder', UserController::class."@retrieveCoderProjects");
            Route::get('/researcher', UserController::class."@retrieveResearcherProjects");
        });

        Route::get('/encodings', UserController::class."@retrieveEncodings");

    });

    Route::group(['prefix' => 'publications'], function () {
        Route::post('/', PublicationController::class."@create");
        Route::get('/', PublicationController::class."@search");
        Route::get('/{publication}', PublicationController::class."@retrieve");
        Route::put('/{publication}', PublicationController::class."@update");
        Route::delete('/{publication}', PublicationController::class."@delete");
    });

            ////    PROJECTS    ////
    Route::group(['prefix' => 'projects'], function () {
        Route::post('/', ProjectController::class."@create");
        Route::get('/{project}', ProjectController::class."@retrieve");
        Route::put('/{project}', ProjectController::class."@update");
        Route::delete('/{project}', ProjectController::class."@delete");

        Route::group(['prefix' => '{project}'], function () {
            Route::get('/forms', ProjectController::class.'@retrieveForms');
            Route::post('/forms', FormController::class."@create");

            Route::get('/publications', ProjectController::class."@retrievePublications");

            Route::group(['prefix' => 'publications'], function () {
                Route::post('/{publication}', ProjectController::class."@addPublication");
                Route::delete('/{publication}', ProjectController::class."@removePublication");
            });
        });

    });

            ////    FORMS       ////
    Route::group(['prefix' => 'forms'], function () {
        Route::get('{form}', FormController::class."@retrieve");
        Route::get('/', FormController::class."@search");
        Route::put('/{form}', FormController::class."@update");
        Route::delete('{form}', FormController::class."@delete");

        Route::group(['prefix' => '{form}/questions'], function() {
            Route::post('/', QuestionController::class."@createQuestion");
            Route::post('{question}', FormController::class."@addQuestion");
            Route::put('{question}', FormController::class."@moveQuestion");
            Route::delete('{question}', FormController::class."@removeQuestion");
        });

        Route::group(['prefix' => '{form}/categories'], function () {
            Route::post('/', CategoryController::class."@create");
            Route::put('/{category}', CategoryController::class."@update");
            Route::delete('/{category}', CategoryController::class."@delete");
        });
    });

    ////    QUESTIONS    ////
    Route::group(['prefix' => 'questions'], function () {
        Route::post('/', QuestionController::class."@create");
        Route::get('/{question}', QuestionController::class."@retrieve");
        Route::put('/{question}', QuestionController::class."@update");
        Route::delete('/{question}', QuestionController::class."@delete");
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{category}', CategoryController::class."@retrieve");
    });

            ////    ENCODINGS    ////
    Route::group(['prefix' => 'encodings'], function () {
        Route::get('/{encoding}', EncodingController::class."@retrieve");
        Route::put('/{encoding}', EncodingController::class."@update");
        Route::group(['prefix' => '{encoding}/branches'], function () {
            Route::post('/', EncodingController::class."@createBranch");
            Route::delete('/{branch}', EncodingController::class."@deleteBranch");
            Route::post('/{branch}/responses', EncodingController::class."@createBranchResponse");
        });
        Route::post('/{encoding}/responses', EncodingController::class."@createSimpleResponse");

    });

    Route::group(['prefix' => 'assignments'], function () {
        Route::post('/manual', AssignmentController::class."@assignOne");
    });

            ////    RESPONSES    ////
    Route::group(['prefix' => 'responses'], function () {

    });

});