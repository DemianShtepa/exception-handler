<?php

namespace App\Providers;

use App\Domain\Entities\User;
use App\Domain\Exceptions\ApiToken\TokenNotFoundException;
use App\Domain\Repositories\ApiTokenRepository;
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
                /** @var User $user */
                $user = $apiTokenRepository->getByToken($request->bearerToken() ?? '')->getUser();
            } catch (TokenNotFoundException $exception) {
                return null;
            }

            return $user;
        });
    }
}
