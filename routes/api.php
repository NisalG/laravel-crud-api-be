<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

 /**
 * @OA\PathItem(
 *     path="/posts",
 *     description="API Endpoints of Posts",
 *     @OA\Tag(name="Posts", description="API Endpoints of Posts")
 * )
 */
Route::group(['prefix' => 'posts', 'middleware' => 'auth:sanctum'], function () {    
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    Route::get('/{id}', [PostController::class, 'show']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
    Route::get('/answers', [PostController::class, 'getAnswers']);
});
