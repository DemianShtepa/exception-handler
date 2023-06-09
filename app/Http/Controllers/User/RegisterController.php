<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\Registrar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController
{
    private Registrar $registrar;

    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;
    }

    public function register(Request $request): JsonResponse
    {
        $apiToken = $this->registrar->register(
            $request->get('name', ''),
            $request->get('email', ''),
            $request->get('password', '')
        );

        return new JsonResponse(['api_token' => $apiToken->getToken()], Response::HTTP_CREATED);
    }
}
