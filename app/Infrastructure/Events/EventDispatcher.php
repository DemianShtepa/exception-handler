<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Domain\Events\Interfaces\Event;
use App\Domain\Events\Interfaces\EventDispatcher as EventDispatcherInterface;
use Illuminate\Contracts\Events\Dispatcher;

final class EventDispatcher implements EventDispatcherInterface
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(Event $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
