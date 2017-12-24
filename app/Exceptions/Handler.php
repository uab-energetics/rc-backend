<?php

namespace App\Exceptions;

use Exception;
use HttpResponseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Router;
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
        parent::report($exception);
    }

    public function render($request, Exception $e)
    {
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
                'msg' => "This is not the route you are looking for"
            ], 404);
        }
        if ($e instanceof ValidationException) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 400);
        }

        return $this->prepareJsonResponse($request, $e);
    }

    protected function getEndOfClassName($className) {
        preg_match('/\w+$/', $className, $matches);
        return $matches[0];
    }
}
