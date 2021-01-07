<?php

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\VirtualProject\VirtualProjectController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/request-reset-password/{email}', [ResetPasswordController::class, 'requestResetPassword']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword']);
Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/virtual-projects', [VirtualProjectController::class, 'getAll']);
    Route::post('/virtual-projects', [VirtualProjectController::class, 'create']);
    Route::post('/virtual-project/{inviteToken}/subscribe', [VirtualProjectController::class, 'subscribe']);
});
