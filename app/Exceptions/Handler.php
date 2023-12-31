<?php

namespace App\Exceptions;

use App\Exceptions\Validations\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            // Handle the UnauthorizedHttpException and return a 401 Unauthorized response
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($exception instanceof ValidationException) {
            // Handle ValidationException
            return response()->json(['message' => 'Validation error', 'errors' => $exception->getMessage()], 422);
        }

        return parent::render($request, $exception);
    }
}
