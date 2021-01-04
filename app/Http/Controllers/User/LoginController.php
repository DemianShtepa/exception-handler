<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\Authenticator;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LoginController
{
    private Authenticator $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function login(Request $request): JsonResponse
    {
        $apiToken = $this->authenticator->login(
            new Email($request->get('email', '')),
            new CleanPassword($request->get('password', ''))
        );

        return new JsonResponse(['api_token' => $apiToken->getToken()]);
    }
}
