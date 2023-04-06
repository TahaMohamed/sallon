<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->errorResponse(message: $exception->getMessage(), code: 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    public function render($request, $exception)
    {
        if ($request->expectsJson()) {
            return match (true) {
                $exception instanceof PostTooLargeException =>  $this->apiResource(status: false, message: $exception->getMessage(), code: 400),
                $exception instanceof AuthenticationException =>  $this->apiResource(status: false, message: $exception->getMessage(), code: 401),
                $exception instanceof ThrottleRequestsException =>  $this->apiResource(status: false, message: $exception->getMessage(), code: 429),
                $exception instanceof ModelNotFoundException ||
                $exception instanceof NotFoundHttpException  =>  $this->apiResource(status: false, message: $exception->getMessage(), code: 404),
                $exception instanceof ValidationException =>  $this->invalidJson($request, $exception),
                default => $this->apiResource(status: false, message: $exception->getMessage() . " in " . $exception->getFile() . " at line " . $exception->getLine(), code: 500)
            };
        }

        return parent::render($request, $exception);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->errorResponse(data: $exception->validator?->errors()?->toArray(), message: $exception->validator?->messages()->first(), code: $exception->status);
    }
}
