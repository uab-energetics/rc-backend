<?php

namespace App\Exceptions;

use Exception;
use HttpResponseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            $sentry = app('sentry');
            $user = Auth::user();
            if ($user !== null) {
                $sentry->user_context($user->toArray());
            }
            $sentry->captureException($exception);
            return;
        }
        parent::report($exception);
    }

    public function render($request, Exception $e)
    {
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'status' => $e->getMessage()
            ], 401);
        }
        if ($e instanceof ModelNotFoundException) {
            $class = $this->getEndOfClassName($e->getModel());
            return response()->json([
                'status' => 'RESOURCE_NOT_FOUND',
                'msg' => 'Could not find the specified '.$class,
            ], 404);
        }
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => "URL_NOT_FOUND",
                'msg' => "This is not the route you are looking for",
                'details' => \Request::fullUrl()
            ], 404);
        }
        if ($e instanceof ValidationException) {
            return invalidParamMessage($e->validator);
        }
        if ($e instanceof ProjectResearcherCountException) {
            return response()->json([
                'status' => 'PROJECT_RESEARCHER_COUNT',
                'msg' => "Projects must have at least one researcher"
            ], 403);
        }
        if ($e instanceof TaskAlreadyStartedException) {
            return response()->json([
                'status' => 'TASK_ALREADY_STARTED',
                'msg' => "This task already has an encoding"
            ], 400);
        }
        if ($e instanceof RepoNotFoundException) {
            return response()->json([
                'status' => 'REPO_NOT_FOUND',
                'msg' => $e->getMessage()
            ], $e->getCode());
        }

        return $this->prepareJsonResponse($request, $e);
    }

    protected function getEndOfClassName($className) {
        preg_match('/\w+$/', $className, $matches);
        return $matches[0];
    }
}
