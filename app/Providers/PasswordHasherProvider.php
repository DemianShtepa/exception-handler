<?php

namespace App\Providers;

use App\Domain\Services\User\Interfaces\PasswordHasher as PasswordHasherInterface;
use App\Infrastructure\Services\User\PasswordHasher;
use Illuminate\Support\ServiceProvider;

class PasswordHasherProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerPasswordHasher();
    }

    private function registerPasswordHasher(): void
    {
        $this->app->bind(PasswordHasherInterface::class, function () {
            return new PasswordHasher();
        });
    }
}
