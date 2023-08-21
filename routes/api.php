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
    'prefix' => 'be',
    'middleware' => ['auth:sanctum', 'admin.permission'],
], function () {
    Route::get('demo', function () {
        return response('OK');
    });
    // Auth route
    Route::post('auth/login', [Admin\AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
    Route::post('auth/register', [Admin\AuthController::class, 'register']);
    Route::get('user/profile', [Admin\AuthController::class, 'profile']);
    Route::any('auth/logout', [Admin\AuthController::class, 'logout']);

    Route::get('protected-resource', [Admin\ProtectedSampleController::class, 'sampleProtected']);

    // Menu route
    Route::resources([
        'user' => Admin\UserController::class,
        'menu' => Admin\MenusController::class,
        'permission' => Admin\PermissionsController::class,
        'role' => Admin\RolesController::class
    ]);
});

Route::fallback(function () {
    return response()->json([
        'system' => env('SYSTEM_INFO', 'NT')
    ]);
});
