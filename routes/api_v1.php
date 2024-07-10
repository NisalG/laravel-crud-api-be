<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\UserController;

// Route::prefix('v1')
// // ->namespace('Api\V1')
// ->group(function () {
//     Route::get('/users', 'UserController@index');
//     // Add more routes here
// });  //doesn't work

// Route::get('/users', 'UserController@index');  //doesn't work

Route::middleware('throttle:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']); //Users to check if API V1 works
});
