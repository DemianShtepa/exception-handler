<?php

namespace App\Providers;

use App\Domain\Services\User\Interfaces\ResetPasswordService as ResetPasswordServiceInterface;
use App\Domain\Services\User\ResetPasswordService;
use App\Infrastructure\Services\User\TransactionalResetPasswordService;
use Illuminate\Support\ServiceProvider;

class ResetPasswordServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerResetPasswordService();
    }

    private function registerResetPasswordService(): void
    {
        $this->app->bind(ResetPasswordServiceInterface::class, function ($app) {
            return new TransactionalResetPasswordService($app->get('em'), $app->make(ResetPasswordService::class));
        });
    }
}
