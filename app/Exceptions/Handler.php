<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        //HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {
            return $e->getResponse();
        }

        if ($e instanceof HttpException) {
            $message = $e->getStatusCode() === 405 ? 'Method not allowed' : $e->getMessage();
            return response()->json(['status' => $e->getStatusCode(), 'message' => $message],
                $e->getStatusCode());
        }

        if ($this->isProduction()) {
            return response()->json(['status' => 500, 'message' => 'Internal server error'], 500);
        }

        return response()->json(['status' => $e->getCode(), 'message' => $e->getMessage()], 500);
    }

    private function isProduction()
    {
        return (env('APP_ENV') === 'production');
    }
}
