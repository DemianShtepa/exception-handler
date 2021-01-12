<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\Status;

use App\Domain\Exceptions\Status\StatusException;

abstract class Status
{
    protected string $name;

    /** @throws StatusException */
    abstract public function setToUnhandled(): Status;

    /** @throws StatusException */
    abstract public function setToHandled(): Status;

    /** @throws StatusException */
    abstract public function setToSolved(): Status;

    public function getName(): string
    {
        return $this->name;
    }
}
