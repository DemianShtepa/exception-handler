<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\Status;

use App\Domain\Exceptions\Status\StatusException;

class SolvedStatus extends Status
{
    protected string $name = 'solved';

    public function setToUnhandled(): Status
    {
        throw new StatusException('Exception can not be switched to this status.');
    }

    public function setToHandled(): Status
    {
        return new HandledStatus();
    }

    public function setToSolved(): Status
    {
        throw new StatusException('Exception is already in this status.');
    }
}
