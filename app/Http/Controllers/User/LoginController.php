<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\Authenticator;
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
            $request->get('email', ''),
            $request->get('password', '')
        );

        return new JsonResponse(['api_token' => $apiToken->getToken()]);
    }
}
