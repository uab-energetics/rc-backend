<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProjectController;
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

    Route::group(['prefix' => 'users'], function() {
        Route::get('projects', UserController::class."@retrieveResearcherProjects");
        Route::get('projects/coder', UserController::class."@retrieveCoderProjects");
        Route::get('projects/researcher', UserController::class."@retrieveResearcherProjects");
    });

            ////    PROJECTS    ////
    Route::group(['prefix' => 'projects'], function () {
        Route::post('/', ProjectController::class."@create");
        Route::get('/{project}', ProjectController::class."@retrieve");
        Route::get('/{project}/forms', ProjectController::class.'@retrieveForms');
        Route::post('/{project}/forms', FormController::class."@create");
    });

            ////    FORMS       ////
    Route::group(['prefix' => 'forms'], function () {
        Route::get('{form}', FormController::class."@retrieve");
        Route::delete('{form}', FormController::class."@delete");

        Route::group(['prefix' => '{form}/questions'], function() {
            Route::post('/', QuestionController::class."@createQuestion");
            Route::post('{question}', FormController::class."@addQuestion");
            Route::put('{question}', FormController::class."@moveQuestion");
        });

        Route::group(['prefix' => '{form}/categories'], function () {
            Route::post('/', CategoryController::class."@create");
            Route::put('/{category}', CategoryController::class."@update");
            Route::delete('/{category}', CategoryController::class."@delete");
        });
    });



            ////    ENCODINGS    ////
    Route::group(['prefix' => 'encodings'], function () {

    });

            ////    RESPONSES    ////
    Route::group(['prefix' => 'responses'], function () {

    });

            ////    QUESTIONS    ////
    Route::group(['prefix' => 'questions'], function () {
        Route::post('/', QuestionController::class."@create");
    });

});