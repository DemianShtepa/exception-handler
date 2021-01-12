<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\Status;

use App\Domain\Exceptions\Status\StatusException;

class HandledStatus extends Status
{
    protected string $name = 'handled';

    public function setToUnhandled(): Status
    {
        return new UnhandledStatus();
    }

    public function setToHandled(): Status
    {
        throw new StatusException('Exception is already in this state.');
    }

    public function setToSolved(): Status
    {
        return new SolvedStatus();
    }
}
