<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\PostController;
use App\Http\Controllers\Api\V2\UserController;

use App\Http\Controllers\Api\V2\CoinGeckoController;

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

Route::get('/users', [UserController::class, 'index']); //Users to check if API V1 works

Route::get('cryptocurrency/{id}', [CoinGeckoController::class, 'getCryptocurrencyData']);
Route::get('market-data', [CoinGeckoController::class, 'getMarketData']);