<?php

declare(strict_types=1);

namespace App\Domain\Events\Interfaces;

interface EventDispatcher
{
    public function dispatch(Event $event): void;
}
