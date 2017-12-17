<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $trace = [];
        foreach ($exception->getTrace() as $item) {
            $trace[] = $item['file'] . ':' . $item['line'] . '     ' . $item['class'] . '::' . $item['function'];
        }
        $json = [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile() . ":" . $exception->getLine(),
            'trace' => $trace
        ];
        return response()->json($json, 500);
    }
}
