<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\PostController;
use App\Http\Controllers\Api\V2\UserController;
use App\Http\Controllers\Api\V2\CategoryController;
use App\Http\Controllers\Api\V2\AWSController;
use App\Http\Controllers\Api\V2\CoinGeckoController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;

// Middlewares
use App\Http\Middleware\RoleManagement;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\Localization;

use App\Http\Controllers\Api\V2\AuthorController;
use App\Services\EmailProviders\MailChimp;
use App\Services\EmailProviders\SendGrid;

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
    Route::get('/{id}', [PostController::class, 'show'])->where('id', '[0-9]+');// Restrict to numeric ids to avoid clash with /answer route
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
    Route::get('/answers', [PostController::class, 'getAnswers']);
});

Route::group(['prefix' => 'categories', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

// Route::prefix('v2')->group(function () {
Route::get('/users', [UserController::class, 'index']); //Users to check if API V2 works
Route::post('/users', [UserController::class, 'store']);
// });

Route::get('cryptocurrency/{id}', [CoinGeckoController::class, 'getCryptocurrencyData']);
Route::get('market-data', [CoinGeckoController::class, 'getMarketData']);

// Route for Role-Based Access Control Middleware Testing
// The route uses the RoleManagement and passes 'admin' as a parameter to it.
Route::middleware([RoleManagement::class . ':admin'])->get('/admin', function () {
    // Routes accessible only by admin users
    return response()->json(['message' => 'Admin Area']);
});

// Route for Logging User Activity Middleware Testing
Route::middleware([LogUserActivity::class])->get('/profile', function () {
    return response()->json(['message' => 'User Profile']);
});

// Route group for Localization Middleware with an alias Testing
Route::middleware(['localize'])->group(function () {
    Route::post('/set-locale', function () {
        return response()->json(['message' => 'Set Locale']);
    });

    Route::get('/current-locale', function () {
        return response()->json(['locale' => App::getLocale()]);
    });
});
// Route for AWS SES Email Testing
Route::post('/send-email', [AWSController::class, 'sendTestSESEmail']);

// Route for AWS SQS Queue Testing
Route::post('/send-message', [AWSController::class, 'sendSQSMessage']);
Route::post('/receive-messages', [AWSController::class, 'receiveSQSMessages']);

// Route for Redis`s Cache Testing
Route::get('/test-redis', function () {
    Cache::put('test-key', 'test-value', 10);
    return Cache::get('test-key');
});

Route::post('/subscribe/mailchimp', [AuthorController::class, 'subscribeToMailList'])->defaults('emailProvider', new MailChimp);
Route::post('/subscribe/sendgrid', [AuthorController::class, 'subscribeToMailList'])->defaults('emailProvider', new SendGrid);

// Route::middleware('auth:sanctum')->post('/broadcasting/auth', [BroadcastController::class, 'auth']);

// Route::group(['middleware' => 'auth:sanctum'], function () {
    Broadcast::routes(); 
// });