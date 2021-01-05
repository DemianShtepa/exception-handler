<?php

namespace App\Providers;

use App\Domain\Events\Interfaces\EventDispatcher as EventDispatcherInterface;
use App\Infrastructure\Events\EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventDispatcherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerEventDispatcher();
    }

    private function registerEventDispatcher(): void
    {
        $this->app->bind(EventDispatcherInterface::class, function ($app) {
            return new EventDispatcher($app->get(Dispatcher::class));
        });
    }
}
