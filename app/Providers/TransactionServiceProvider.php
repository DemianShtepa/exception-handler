<?php

namespace App\Providers;

use App\Domain\Services\Transcation\Transaction as TransactionInterface;
use App\Infrastructure\Services\Transaction\Transaction;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerTransaction();
    }

    private function registerTransaction(): void
    {
        $this->app->bind(TransactionInterface::class, function ($app) {
            return new Transaction($app['em']);
        });
    }
}
