<?php

namespace App\Providers;

use App\Domain\Services\User\Interfaces\PasswordResetter as PasswordResetterInterface;
use App\Domain\Services\User\PasswordResetter;
use App\Infrastructure\Services\User\TransactionalPasswordResetter;
use Illuminate\Support\ServiceProvider;

class ResetPasswordServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerResetPasswordService();
    }

    private function registerResetPasswordService(): void
    {
        $this->app->bind(PasswordResetterInterface::class, function ($app) {
            return new TransactionalPasswordResetter($app->get('em'), $app->make(PasswordResetter::class));
        });
    }
}
