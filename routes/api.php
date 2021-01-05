<?php

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\VirtualProject\VirtualProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/request-reset-password', [ResetPasswordController::class, 'requestResetPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/virtual-projects', [VirtualProjectController::class, 'getAll']);
    Route::post('/virtual-project', [VirtualProjectController::class, 'create']);
});
