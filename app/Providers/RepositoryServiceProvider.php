<?php

namespace App\Providers;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Repositories\ApiTokenRepository as ApiTokenRepositoryInterface;
use App\Domain\Repositories\ResetPasswordRequestRepository as ResetPasswordRequestRepositoryInterface;
use App\Domain\Repositories\UserRepository as UserRepositoryInterface;
use App\Domain\Repositories\VirtualProjectRepository as VirtualProjectRepositoryInterface;
use App\Infrastructure\Repositories\Doctrine\ApiTokenRepository;
use App\Infrastructure\Repositories\Doctrine\ResetPasswordRequestRepository;
use App\Infrastructure\Repositories\Doctrine\UserRepository;
use App\Infrastructure\Repositories\Doctrine\VirtualProjectRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerUserRepository();
        $this->registerApiTokenRepository();
        $this->registerResetPasswordRequestRepository();
        $this->registerVirtualProjectRepository();
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

    private function registerResetPasswordRequestRepository(): void
    {
        $this->app->bind(ResetPasswordRequestRepositoryInterface::class, function ($app) {
            return new ResetPasswordRequestRepository(
                $app['em'],
                $app['em']->getClassMetaData(ResetPasswordRequest::class)
            );
        });
    }

    private function registerVirtualProjectRepository(): void
    {
        $this->app->bind(VirtualProjectRepositoryInterface::class, function ($app) {
            return new VirtualProjectRepository(
                $app['em'],
                $app['em']->getClassMetaData(VirtualProject::class)
            );
        });
    }
}
