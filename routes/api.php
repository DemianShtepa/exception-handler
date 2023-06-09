<?php

use App\Http\Controllers\Exception\ExceptionController;
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
    Route::group([
        'prefix' => 'virtual-projects'
    ], function () {
        Route::get('/', [VirtualProjectController::class, 'getAll']);
        Route::post('/', [VirtualProjectController::class, 'create']);
        Route::post('/{inviteToken}/subscribe', [VirtualProjectController::class, 'subscribe']);
        Route::delete('/{virtualProjectId}/unsubscribe', [VirtualProjectController::class, 'unsubscribe']);
        Route::put('/{virtualProjectId}/update-name', [VirtualProjectController::class, 'updateName']);
        Route::put('/{virtualProjectId}/change-invite-token', [VirtualProjectController::class, 'changeInviteToken']);
        Route::put('/{virtualProjectId}/change-push-token', [VirtualProjectController::class, 'changePushToken']);
    });
    Route::group([
        'prefix' => 'exceptions'
    ], function () {
        Route::put('/{exceptionId}/unhandle', [ExceptionController::class, 'setToUnhandled']);
        Route::put('/{exceptionId}/handle', [ExceptionController::class, 'setToHandled']);
        Route::put('/{exceptionId}/solve', [ExceptionController::class, 'setToSolved']);
    });
});
Route::post('/exceptions/{pushToken}', [ExceptionController::class, 'create']);
