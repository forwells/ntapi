<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

/*
|------------------------------------------------------------------------
| API 路由
|------------------------------------------------------------------------
|
|您可以在此处为您的应用程序注册 API 路由。这些路由由组内的 RouteServiceProvider 加载
*/


Route::group([
    'middleware' => 'auth:admin',
    'prefix' => 'be',
    'except' => ['login', 'demo']
], function () {
    Route::get('demo', function () {
        return response('OK');
    });
    Route::post('auth/login', [Admin\AuthController::class, 'login'])->withoutMiddleware('auth:admin');
    Route::post('auth/register', [Admin\AuthController::class, 'register'])->withoutMiddleware('auth:admin');
    Route::get('user/profile', [Admin\AuthController::class, 'profile']);
});
