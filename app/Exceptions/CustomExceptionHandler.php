<?php

namespace App\Exceptions;

// use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomExceptionHandler extends ExceptionHandler
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
     * Report or log an exception.
     */
    public function report(Throwable $e)
    {
        // Custom reporting logic - Log the exception to a log file
        Log::info('app\Exceptions\CustomExceptionHandler.php >> report() method called.');
        Log::error($e->getMessage(), ['exception' => $e]);

        // Optionally send the exception to an external service like Sentry
        if (app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Custom rendering logic
        Log::info('app\Exceptions\CustomExceptionHandler.php >> render() method called.');

        //------------------------------------------------------
        // This section will not work since we handle the validation errors in actions
        // Check if the exception is a validation exception
        if ($e instanceof ValidationException) {
            $err = [];
            foreach ($e->validator->errors()->toArray() as $key => $error) {
                foreach ($error as $sub_error) {
                    $err[$key] = $sub_error;
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'code' => $e->status, // HTTP status code (422 Unprocessable Entity in this case).
                    'result' => $err, // Above restructured validation errors.
                    'success' => false // A boolean indicating the failure.
                ], 422);
            }
        } else {
            // Custom rendering logic for general exceptions
            if ($request->wantsJson()) {
                return response()->json([
                    'code' => 500, // HTTP status code
                    'message' => 'A custom exception occurred: ' . $e->getMessage(),
                    'success' => false // A boolean indicating the failure.
                ], 500);
            }
        }
        //------------------------------------------------------

        return parent::render($request, $e);
    }
}
