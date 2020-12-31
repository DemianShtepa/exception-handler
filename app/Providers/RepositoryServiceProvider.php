<?php

namespace App\Providers;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository as UserRepositoryInterface;
use App\Infrastructure\Repositories\Doctrine\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerUserRepository();
    }

    private function registerUserRepository(): void
    {
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            return new UserRepository($app['em'], $app['em']->getClassMetaData(User::class));
        });
    }
}
