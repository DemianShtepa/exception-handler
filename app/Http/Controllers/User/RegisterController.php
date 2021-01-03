<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Services\User\Registrar;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
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
            new Name($request->get('name', '')),
            new Email($request->get('email', '')),
            new CleanPassword($request->get('password', ''))
        );

        return new JsonResponse(['api_token' => $apiToken->getToken()], Response::HTTP_CREATED);
    }
}
