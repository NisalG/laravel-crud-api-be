<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Implementation for V2
        return response()->json(['version' => 'v2', 'users' => []]);
    }
}
