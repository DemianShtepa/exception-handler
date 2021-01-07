<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\RequestResetPasswordService;
use App\Domain\ValueObjects\User\Email;
use App\Infrastructure\Services\User\TransactionalResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ResetPasswordController
{
    private RequestResetPasswordService $requestResetPasswordService;

    private TransactionalResetPasswordService $resetPasswordService;

    public function __construct(
        TransactionalResetPasswordService $resetPasswordService,
        RequestResetPasswordService $requestResetPasswordService
    ) {
        $this->requestResetPasswordService = $requestResetPasswordService;
        $this->resetPasswordService = $resetPasswordService;
    }

    public function requestResetPassword(Request $request, string $email): JsonResponse
    {
        $this->requestResetPasswordService->requestResetPassword(new Email($email));

        return new JsonResponse(['message' => 'Reset password requested.']);
    }

    public function resetPassword(Request $request, string $token): JsonResponse
    {
        $this->resetPasswordService->resetPassword(
            $token,
            $request->get('password', '')
        );

        return new JsonResponse(['message' => 'Password reset.']);
    }
}
