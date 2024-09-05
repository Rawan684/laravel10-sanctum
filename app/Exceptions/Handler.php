<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            switch (get_class($e)) {
                case AuthenticationException::class:
                    return response()->json(['error' => 'Unauthorized'], 401);
                case AccessDeniedHttpException::class:
                    return response()->json(['error' => 'Forbidden'], 403);
                case NotFoundHttpException::class:
                    return response()->json(['error' => 'Not Found'], 404);
                case ValidationException::class:
                    return response()->json(['error' => 'Validation errors', 'errors' => $e->validator->errors()], 422);
                case TooManyRequestsHttpException::class:
                    return response()->json(['error' => 'Too Many Requests'], 429);
                case QueryException::class:
                    return response()->json(['error' => 'Database Error'], 500);
                default:
                    // handle other exceptions or return a generic error response
                    return response()->json(['error' => 'Internal Server Error'], 500);
            }
        });
    }
}
