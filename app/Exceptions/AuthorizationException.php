<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;

class AuthorizationException extends Exception
{
    public function __construct($message = "Unauthorized action", $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // Report or log an exception.
    public function report()
    {
        Log::warning("AuthorizationException: {$this->getMessage()}");
    }

    // Render an exception into an HTTP response.
    public function render($request)
    {
        return response()->json([
            'error' => 'AuthorizationException',
            'message' => $this->getMessage()
        ], 403);
    }
}
