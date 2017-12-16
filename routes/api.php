<?php

use App\Http\Controllers\ProjectController;
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

            ////    PROJECTS    ////
    Route::group(['prefix' => 'projects'], function () {
        Route::post('/', ProjectController::class."@create");
    });

            ////    FORMS       ////
    Route::group(['prefix' => 'forms'], function () {

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