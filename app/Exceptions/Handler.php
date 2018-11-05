<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
        BaseException::class,
        AuthPasswordChangeException::class,
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
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if ( ! app()->environment('local') &&
            app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Catch Validation Errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation failed!',
                'errors' => $exception->errors(),
            ], 200);
        }

        // Catch authentication errors
        if ($exception instanceof AuthenticationException || $exception instanceof InvalidSignatureException) {
            return response()->json([
                'code' => 401,
                'message' => $exception->getMessage(),
            ], 401);
        }

        // Catch authentication errors
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'code' => 403,
                'message' => $exception->getMessage(),
            ], 403);
        }

        if ($exception instanceof BaseException) {
            return response()->json([
                'code' => 405,
                'message' => $exception->getMessage(),
            ], 200);
        }

        if ($exception instanceof AuthPasswordChangeException) {
            return response()->json([
                'code' => 419,
                'message' => $exception->getMessage(),
            ], 419);
        }

        // Catch model not found db error
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'code' => 404,
                'message' => 'Not Found!',
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
