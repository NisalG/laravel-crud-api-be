<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Implementation for V1
        return response()->json(['version' => 'v1', 'users' => []]);
    }
}
