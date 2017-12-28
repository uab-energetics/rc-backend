<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConflictsController;
use App\Http\Controllers\EncodingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectInvitesController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', AuthController::class."@login");
    Route::post('register', AuthController::class."@register");
});


Route::group(['middleware' => 'jwt.auth'], function () {

    $forms_ctrl = FormController::class;
    $encodings_ctrl = EncodingController::class;
    $notifications_ctrl = NotificationsController::class;
    $project_invites = ProjectInvitesController::class;
    $comment_ctrl = \App\Http\Controllers\CommentsController::class;


    // users
    Route::put(     '/my-profile', UserController::class."@updateProfile");
    Route::get(     'users', UserController::class."@search");
    Route::get(     'users/projects', UserController::class."@retrieveResearcherProjects");
    Route::get(     'users/projects/coder', UserController::class."@retrieveCoderProjects");
    Route::get(     'users/projects/researcher', UserController::class."@retrieveResearcherProjects");
    Route::get(     'users/encodings', UserController::class."@retrieveEncodings");

    // publications
    Route::post(    'publications/', PublicationController::class."@create");
    Route::get(     'publications/', PublicationController::class."@search");
    Route::get(     'publications/{id}', getter(PublicationController::class));
    Route::put(     'publications/{id}', PublicationController::class."@update");
    Route::delete(  'publications/{id}', PublicationController::class."@delete");

    // projects
    Route::post(    'projects', ProjectController::class."@create");
    Route::get(     'projects', ProjectController::class."@search");
    Route::get(     'projects/{id}', getter(\App\Project::class));
    Route::put(     'projects/{id}', ProjectController::class."@update");
    Route::delete(  'projects/{id}', ProjectController::class."@delete");
    Route::get(     'projects/{id}/forms', ProjectController::class.'@retrieveForms');
    Route::post(    'projects/{id}/forms', FormController::class."@create");
    Route::get(     'projects/{id}/publications', ProjectController::class."@retrievePublications");
    Route::post(    'projects/{id}/publications', PublicationController::class."@createInProject");
    Route::post(    'projects/{id}/publications/{publication}', ProjectController::class."@addPublication");
    Route::delete(  'projects/{id}/publications/{publication}', ProjectController::class."@removePublication");
    Route::get(     'projects/{id}/researchers', ProjectController::class."@getResearchers");
    Route::post(    'projects/{id}/invite-researcher', ProjectController::class."@inviteResearcher");


    // forms
    Route::get(     'forms', "$forms_ctrl@search");
    Route::get(     'forms/{form}', "$forms_ctrl@retrieve");
    Route::put(     'forms/{form}', "$forms_ctrl@update");
    Route::delete(  'forms/{form}', "$forms_ctrl@delete");
    Route::get(     'forms/{form}/export', "$forms_ctrl@export");
    Route::post(    'forms/{form}/questions', QuestionController::class."@createQuestion");
    Route::post(    'forms/{form}/questions/{question}', "$forms_ctrl@addQuestion");
    Route::put(     'forms/{form}/questions/{question}', "$forms_ctrl@moveQuestion");
    Route::delete(  'forms/{form}/questions/{question}', "$forms_ctrl@removeQuestion");
    Route::post(    'forms/{form}/categories', CategoryController::class."@create");
    Route::put(     'forms/{form}/categories/{category}', CategoryController::class."@update");
    Route::delete(  'forms/{form}/categories/{category}', CategoryController::class."@delete");

    // questions
    Route::post(    'questions/', QuestionController::class."@create");
    Route::get(     'questions/{question}', QuestionController::class."@retrieve");
    Route::get(     'questions/', QuestionController::class."@search");
    Route::put(     'questions/{question}', QuestionController::class."@update");
    Route::delete(  'questions/{question}', QuestionController::class."@delete");

    // categories
    Route::get(     'categories/{category}', CategoryController::class."@retrieve");

    // encodings
    Route::get(     'encodings/{encoding}', "$encodings_ctrl@retrieve");
    Route::put(     'encodings/{encoding}', "$encodings_ctrl@update");
    Route::delete(  'encodings/{encoding}', "$encodings_ctrl@delete");
    Route::post(    'encodings/{encoding}/branches/', "$encodings_ctrl@createBranch");
    Route::delete(  'encodings/{encoding}/branches/{branch}', "$encodings_ctrl@deleteBranch");
    Route::post(    'encodings/{encoding}/branches/{branch}/responses', "$encodings_ctrl@createBranchResponse");
    Route::post(    'encodings/{encoding}/responses', "$encodings_ctrl@createSimpleResponse");

    // conflicts
    Route::get(     'conflict-report/{encoding_id}', ConflictsController::class."@getConflictsReport");

    // assignments
    Route::post(    'assignments/manual', AssignmentController::class."@assignOne");

    // notifications
    Route::get(     '/notifications', "$notifications_ctrl@unreadNotifications");
    Route::get(     '/notifications/mark-read', "$notifications_ctrl@markAllRead");

    // invites
    Route::post(    '/invite-to-project', "$project_invites@sendInviteToken");
    Route::post(    '/redeem-invite-token', "$project_invites@redeemInviteToken");

    // comments
    Route::post(    '/channels', "$comment_ctrl@createChannel");
    Route::get(     '/channels/{name}', "$comment_ctrl@getChannel");
    Route::post(    '/channels/{name}/comments', "$comment_ctrl@postInChannel");
    Route::post(    '/comments/{id}/reply', "$comment_ctrl@reply");
    Route::put(     '/comments/{id}', "$comment_ctrl@edit");
    Route::delete(  '/comments/{id}', "$comment_ctrl@delete");
});


Route::get('/validate-invite', ProjectInvitesController::class."@validateInvitation");








/**
*  ===========================================
*  MAILABLE PREVIEWS
*  ===========================================
 *
 * These will be removed in production
 *
*/

Route::get('/mailables/invite-to-project', function(){
    return new \App\Mail\InvitedToProject([
        'user' => "Chris Rocco",
        'project' => "Dummy Project",
        'callback' => 'localhost:8000'
    ]);
});