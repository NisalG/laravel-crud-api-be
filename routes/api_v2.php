<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\PostController;
use App\Http\Controllers\Api\V2\UserController;

use App\Http\Controllers\Api\V2\CoinGeckoController;
use Illuminate\Support\Facades\App;

// Middlewares
use App\Http\Middleware\RoleManagement;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\Localization;

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

Route::prefix('v2')->group(function () {
    Route::get('/users', [UserController::class, 'index']);//Users to check if API V2 works
    Route::post('/users', [UserController::class, 'store']);
});

Route::get('cryptocurrency/{id}', [CoinGeckoController::class, 'getCryptocurrencyData']);
Route::get('market-data', [CoinGeckoController::class, 'getMarketData']);

// Route for Role-Based Access Control Middleware Testing
Route::middleware([RoleManagement::class . ':admin'])->get('/admin', function () {
    // Routes accessible only by admin users
    return response()->json(['message' => 'Admin Area']);
});

// Route for Logging User Activity Middleware Testing
Route::middleware([LogUserActivity::class])->get('/profile', function () {
    return response()->json(['message' => 'User Profile']);
});

// Route group for Localization Middleware Testing
Route::middleware([Localization::class])->group(function () {
    Route::post('/set-locale', function () {
        return response()->json(['message' => 'Set Locale']);
    });

    Route::get('/current-locale', function () {
        return response()->json(['locale' => App::getLocale()]);
    });
});
