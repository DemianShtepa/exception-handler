<?php

namespace App\Providers;

use App\Domain\Events\ResetPasswordRequested;
use App\Infrastructure\Events\Listeners\ResetPasswordRequestListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResetPasswordRequested::class => [
            ResetPasswordRequestListener::class
        ]
    ];

    public function boot()
    {
        //
    }
}
