<?php

namespace App\Providers;

use App\Domain\Entities\User;
use App\Domain\Exceptions\ApiToken\TokenNotFoundException;
use App\Domain\Repositories\ApiTokenRepository;
use DateTimeImmutable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(ApiTokenRepository $apiTokenRepository)
    {
        $this->registerPolicies();

        Auth::viaRequest('api-token', function (Request $request) use ($apiTokenRepository) {
            try {
                $token = $apiTokenRepository->getByToken($request->bearerToken() ?? '');

                if ($token->isExpiredComparedTo(new DateTimeImmutable())) {
                    return null;
                }
                /** @var User $user */
                $user = $token->getUser();
            } catch (TokenNotFoundException $exception) {
                return null;
            }

            return $user;
        });
    }
}
