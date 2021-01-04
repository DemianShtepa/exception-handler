<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Domain\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController
{
    public function getUser(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $data = [
            'name' => $user->getName()->getValue(),
            'email' => $user->getEmail()->getValue()
        ];

        return new JsonResponse($data);
    }
}
