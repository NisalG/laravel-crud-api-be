<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;

class CategoryValidatorService
{
    public static function validateCreateRequest($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    }
}
