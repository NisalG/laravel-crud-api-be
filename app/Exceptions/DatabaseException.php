<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;

class DatabaseException extends Exception
{
    public function __construct($message = "Database error", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // Report or log an exception.
    public function report()
    {
        Log::error("DatabaseException: {$this->getMessage()}");
    }

    // Render an exception into an HTTP response.
    public function render($request)
    {
        return response()->json([
            'error' => 'DatabaseException',
            'message' => $this->getMessage()
        ], 500);
    }
}
