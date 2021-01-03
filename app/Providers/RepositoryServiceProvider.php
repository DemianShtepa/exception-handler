<?php

namespace App\Providers;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Repositories\ApiTokenRepository as ApiTokenRepositoryInterface;
use App\Domain\Repositories\UserRepository as UserRepositoryInterface;
use App\Infrastructure\Repositories\Doctrine\ApiTokenRepository;
use App\Infrastructure\Repositories\Doctrine\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerUserRepository();
        $this->registerApiTokenRepository();
    }

    private function registerUserRepository(): void
    {
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            return new UserRepository($app['em'], $app['em']->getClassMetaData(User::class));
        });
    }

    private function registerApiTokenRepository(): void
    {
        $this->app->bind(ApiTokenRepositoryInterface::class, function ($app) {
            return new ApiTokenRepository($app['em'], $app['em']->getClassMetaData(ApiToken::class));
        });
    }
}
