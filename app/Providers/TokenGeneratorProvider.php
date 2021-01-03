<?php

namespace App\Providers;

use App\Domain\Services\Token\Interfaces\TokenGenerator as TokenGeneratorInterface;
use App\Infrastructure\Services\Token\TokenGenerator;
use Illuminate\Support\ServiceProvider;

class TokenGeneratorProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerTokenGenerator();
    }

    private function registerTokenGenerator(): void
    {
        $this->app->bind(TokenGeneratorInterface::class, function () {
            return new TokenGenerator();
        });
    }
}
