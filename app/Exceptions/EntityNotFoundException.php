<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;

class EntityNotFoundException extends Exception
{
    public function __construct($entity = "Entity", $code = 404, Exception $previous = null)
    {
        parent::__construct("{$entity} not found", $code, $previous);
    }

    // Report or log an exception.
    public function report()
    {
        Log::error("EntityNotFoundException: {$this->getMessage()}");
    }

    // Render an exception into an HTTP response.
    public function render($request)
    {
        return response()->json([
            'error' => 'EntityNotFoundException',
            'message' => $this->getMessage()
        ], 404);
    }
}
