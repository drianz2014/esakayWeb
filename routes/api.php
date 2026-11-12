<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('auth/login', 'login');
});

/**Route::middleware('auth:sanctum')->group(function () {
    //Route::resource('products', ProductController::class);
});**/
