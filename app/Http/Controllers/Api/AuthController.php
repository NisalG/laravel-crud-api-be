<?php

 namespace App\Http\Controllers\Api;

 use App\Http\Controllers\Controller;
 use App\Models\User;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Facades\Auth;

 class AuthController extends Controller
 {
    /**
    * @OA\Post(
    *     path="/api/register",
    *     operationId="registerUser",
    *     tags={"Authentication"},
    *     summary="Register a new user",
    *     description="User Registration Endpoint",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 type="object",
    *                 required={"name","email","password","password_confirmation"},
    *                 @OA\Property(property="name",type="text"),
    *                 @OA\Property(property="email",type="text"),
    *                 @OA\Property(property="password",type="password"),
    *                 @OA\Property(property="password_confirmation",type="password"),
    *             ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response="201",
    *         description="User Registered Successfully",
    *         @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *       response="200",
    *       description="Registered Successfull",
    *       @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *         response="422",
    *         description="Unprocessable Entity",
    *         @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *         response="400",
    *         description="Bad Request",
    *         @OA\JsonContent()
    *     ),
    * )
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate API token for the newly registered user (optional)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,  // You can optionally include user details
            'token' => $token ?? null  // Include token if generated
        ], 201);
    }

    //  Login API
    /**
    * @OA\Post(
    *     path="/api/login",
    *     operationId="loginUser",
    *     tags={"Authentication"},
    *     summary="Login a user",
    *     description="User Login Endpoint",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 type="object",
    *                 required={"email","password"},
    *                 @OA\Property(property="email",type="text"),
    *                 @OA\Property(property="password",type="password"),
    *             ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response="201",
    *         description="User Login Successfully",
    *         @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *       response="200",
    *       description="Login Successfull",
    *       @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *         response="422",
    *         description="Unprocessable Entity",
    *         @OA\JsonContent()
    *     ),
    *     @OA\Response(
    *         response="400",
    *         description="Bad Request",
    *         @OA\JsonContent()
    *     ),
    * )
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        // $user = Auth::user();
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens for the user
            // Alternatively, revoke specific token using its ID
            // $user->token()->revoke(); // Assuming you have token ID
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
 }