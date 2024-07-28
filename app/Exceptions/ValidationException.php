<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;

class ValidationException extends Exception
{
    protected $errors;

    public function __construct($errors = [], $code = 422, Exception $previous = null)
    {
        parent::__construct("Validation error", $code, $previous);
        $this->errors = $errors;
    }

    // Report or log an exception.
    public function report()
    {
        Log::warning("ValidationException: {$this->getMessage()}", ['errors' => $this->errors]);
    }

    // Render an exception into an HTTP response.
    public function render($request)
    {
        return response()->json([
            'error' => 'ValidationException',
            'message' => $this->getMessage(),
            'errors' => $this->errors
        ], 422);
    }
}
