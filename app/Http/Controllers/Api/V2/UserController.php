<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    public function index()
    {
        // Implementation for V2
        return response()->json(['version' => 'v2', 'users' => []]);
    }

    public function store(CreateUserRequest $request)
    {
        $encryptedData = Crypt::encryptString($request->userSSN);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ssn' => $encryptedData,
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function show($id)
    {
        $user = User::find($id); // Find the user by ID

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $encryptedData = $user->ssn;
        try {
            $decryptedData = Crypt::decryptString($encryptedData);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Unable to decrypt data'], 500);
        }

        return response()->json([
            'user' => $user,
            'decrypted_ssn' => $decryptedData,
        ]);
    }
}
