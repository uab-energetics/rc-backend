<?php

use App\Form;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BranchQuestionsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ConflictsController;
use App\Http\Controllers\EncodingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFormController;
use App\Http\Controllers\ProjectInvitesController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Project;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;





Route::group(['prefix' => 'auth'], function () {
    Route::post('login', AuthController::class."@login");
    Route::post('register', AuthController::class."@register");
});


Route::group(['middleware' => ['jwt.auth']], function () {

    $user_ctrl = UserController::class;
    $forms_ctrl = FormController::class;
    $encodings_ctrl = EncodingController::class;
    $notifications_ctrl = NotificationsController::class;
    $project_invites_ctrl = ProjectInvitesController::class;
    $comment_ctrl = CommentsController::class;
    $publications_ctrl = PublicationController::class;
    $projects_ctrl = ProjectController::class;
    $proj_form_ctrl = ProjectFormController::class;
    $questions_ctrl = QuestionController::class;
    $categories_ctrl = CategoryController::class;

    // users
    Route::put(     '/my-profile', "$user_ctrl@updateProfile");
    Route::get(     'users', "$user_ctrl@search");
    Route::get(     'users/projects', "$user_ctrl@retrieveResearcherProjects");
    Route::get(     'users/projects/coder', "$user_ctrl@retrieveCoderProjects");
    Route::get(     'users/projects/researcher', "$user_ctrl@retrieveResearcherProjects");
    Route::get(     'users/encodings', "$user_ctrl@retrieveEncodings");
    Route::get(     'users/tasks',  "$user_ctrl@retrieveTasks");
    Route::get(     'users/forms',  "$user_ctrl@retrieveForms");

    // publications
    Route::post(    'publications/', "$publications_ctrl@create");
    Route::get(     'publications/', searcher(Publication::class));
    Route::get(     'publications/{publication}', getter(Publication::class));
    Route::put(     'publications/{publication}', "$publications_ctrl@update");
    Route::delete(  'publications/{publication}', "$publications_ctrl@delete");

    // projects
    Route::post(    'projects', "$projects_ctrl@create")->middleware('validate:projects.create');
    Route::get(     'projects', "$projects_ctrl@search")->middleware('validate:projects.create');
    Route::get(     'projects/{project}', getter(Project::class));
    Route::put(     'projects/{project}', "$projects_ctrl@update");
    Route::get(     'projects/{project}/dashboard', "$projects_ctrl@getDashboard");
    Route::delete(  'projects/{project}', "$projects_ctrl@delete");
    Route::get(     'projects/{project}/forms', "$projects_ctrl@retrieveForms");
    Route::post(    'projects/{project}/forms', FormController::class."@create");
    Route::get(     'projects/{project}/publications', "$projects_ctrl@retrievePublications");
    Route::post(    'projects/{project}/publications', "$publications_ctrl@createInProject");
    Route::post(    'projects/{project}/publications/csv', "$publications_ctrl@uploadFromCSV");
    Route::post(    'projects/{project}/publications/{publication}', "$projects_ctrl@addPublication");
    Route::delete(  'projects/{project}/publications/{publication}', "$projects_ctrl@removePublication");
    Route::get(     'projects/{project}/encoders', "$projects_ctrl@searchEncoders");
    Route::get(     'projects/{project}/researchers', "$projects_ctrl@searchResearchers");
    Route::post(    'projects/{project}/researchers', "$projects_ctrl@addResearcher");
    Route::delete(  'projects/{project}/researchers/{user}', "$projects_ctrl@removeResearcher");
    Route::post(    'projects/{project}/encoders', "$projects_ctrl@addEncoder");
    Route::delete(  'projects/{project}/encoders/{user}', "$projects_ctrl@removeEncoder");

    Route::get(     'projects/{project}/forms/{form}', $proj_form_ctrl."@getSettings");
    Route::put(     'projects/{project}/forms/{form}', $proj_form_ctrl."@updateSettings");
    Route::get(     'projects/{project}/forms/{form}/inherit-project-publications', $proj_form_ctrl."@inheritProjectPublications");
    Route::get(     'projects/{project}/forms/{form}/inherit-project-encoders', $proj_form_ctrl."@inheritProjectEncoders");
    Route::get(     'projects/{project}/forms/{form}/publications', $proj_form_ctrl."@searchPublications");
    Route::post(    'projects/{project}/forms/{form}/publications/{publication}', $proj_form_ctrl."@addPublication");
    Route::post(    'projects/{project}/forms/{form}/publications', $proj_form_ctrl."@addPublications");
    Route::delete(  'projects/{project}/forms/{form}/publications/{publication}', $proj_form_ctrl."@removePublication");
    Route::get(     'projects/{project}/forms/{form}/encoders', $proj_form_ctrl."@searchEncoders");
    Route::post(    'projects/{project}/forms/{form}/encoders/{user}', $proj_form_ctrl."@addEncoder");
    Route::post(    'projects/{project}/forms/{form}/encoders', $proj_form_ctrl."@addEncoders");
    Route::delete(  'projects/{project}/forms/{form}/encoders/{user}', $proj_form_ctrl."@removeEncoder");
    Route::post(    'projects/{project}/forms/{form}/encoders/{encoder}/request-tasks', $proj_form_ctrl."@requestTasks");



    // forms
    Route::get(     'forms', "$forms_ctrl@search");
    Route::get(     'forms/{form}', getter(Form::class));
    Route::put(     'forms/{form}', "$forms_ctrl@update");
    Route::delete(  'forms/{form}', "$forms_ctrl@delete");
    Route::get(     'forms/{form}/export', "$forms_ctrl@export");
    Route::post(    'forms/{form}/questions', "$questions_ctrl@createQuestion");
    Route::post(    'forms/{form}/questions/{question}', "$forms_ctrl@addQuestion");
    Route::put(     'forms/{form}/questions/{question}', "$forms_ctrl@moveQuestion");
    Route::delete(  'forms/{form}/questions/{question}', "$forms_ctrl@removeQuestion");
    Route::post(    'forms/{form}/categories', "$categories_ctrl@create");
    Route::put(     'forms/{form}/categories/{category}', "$categories_ctrl@updateOnForm");
    Route::delete(  'forms/{form}/categories/{category}', "$categories_ctrl@delete");
    Route::put(     'categories/{category}', "$categories_ctrl@update");

    // questions
    Route::post(    'questions/', "$questions_ctrl@create");
    Route::get(     'questions/{question}', "$questions_ctrl@retrieve");
    Route::get(     'questions/', "$questions_ctrl@search");
    Route::put(     'questions/{question}', "$questions_ctrl@update");
    Route::delete(  'questions/{question}', "$questions_ctrl@delete");

    // categories
    Route::get(     'categories/{category}', "$categories_ctrl@retrieve");

    // encodings
    Route::get(     'encodings/{encoding}', "$encodings_ctrl@retrieve");
    Route::put(     'encodings/{encoding}', "$encodings_ctrl@update");
    Route::delete(  'encodings/{encoding}', "$encodings_ctrl@delete");
    Route::post(    'encodings/{encoding}/branches/', "$encodings_ctrl@createBranch");
    Route::delete(  'encodings/{encoding}/branches/{branch}', "$encodings_ctrl@deleteBranch");
    Route::post(    'encodings/{encoding}/branches/{branch}/responses', "$encodings_ctrl@createBranchResponse");
    Route::post(    'encodings/{encoding}/responses', "$encodings_ctrl@createSimpleResponse");

    // tasks
    Route::get(     'tasks/{task}', TaskController::class."@retrieve");
    Route::get(     'tasks/{task}/start-encoding', TaskController::class."@startEncoding");
    Route::delete(  'tasks/{task}', TaskController::class."@delete");


    // branch question map
    Route::get(     'branches/{branch}/questionMap', BranchQuestionsController::class.'@getQuestions');
    Route::post(    'branches/{branch}/questionMap/{question}', BranchQuestionsController::class.'@addQuestion');
    Route::delete(  'branches/{branch}/questionMap/{question}', BranchQuestionsController::class.'@removeQuestion');

    // conflicts
    Route::get(     'conflict-report/{encoding_id}', ConflictsController::class."@getConflictsReport");

    // assignments
    Route::post(    'assignments/manual', AssignmentController::class."@assignOne");

    // notifications
    Route::get(     '/notifications', "$notifications_ctrl@unreadNotifications");
    Route::get(     '/notifications/mark-read', "$notifications_ctrl@markAllRead");

    // invites
    Route::post(    '/invite-researcher-to-project', "$project_invites_ctrl@sendResearcherInviteToken");
    Route::post(    '/redeem-researcher-invite', "$project_invites_ctrl@redeemResearcherInviteToken");
    Route::post(    '/invite-encoder-to-project', "$project_invites_ctrl@sendEncoderInviteToken");
    Route::post(    '/redeem-encoder-invite', "$project_invites_ctrl@redeemEncoderInviteToken");

    // comments
    Route::post(    '/channels', "$comment_ctrl@createChannel");
    Route::get(     '/channels/{name}', "$comment_ctrl@getChannel");
    Route::post(    '/channels/{name}/comments', "$comment_ctrl@postInChannel");
    Route::post(    '/comments/{comment}/reply', "$comment_ctrl@reply");
    Route::put(     '/comments/{comment}', "$comment_ctrl@edit");
    Route::delete(  '/comments/{comment}', "$comment_ctrl@delete");
});


Route::get('/validate-researcher-invite', ProjectInvitesController::class."@validateResearcherInvitation");
Route::get('/validate-encoder-invite', ProjectInvitesController::class."@validateEncoderInvitation");





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