<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {

    Route::post('login', LoginController::class);
    Route::post('logout', LogoutController::class);
    Route::post('register', RegisterController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::resource('users', UsersController::class);
    Route::resource('posts', PostController::class);
});
