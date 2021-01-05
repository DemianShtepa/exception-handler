<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\ResetPasswordService;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ResetPasswordController
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function requestResetPassword(Request $request): JsonResponse
    {
        $this->resetPasswordService->requestResetPassword(new Email($request->get('email', '')));

        return new JsonResponse(['message' => 'Reset password requested.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $this->resetPasswordService->resetPassword(
            $request->get('token', ''),
            new CleanPassword($request->get('password', ''))
        );

        return new JsonResponse(['message' => 'Password reset.']);
    }
}
