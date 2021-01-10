<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

final class ForbiddenException extends DomainException
{
    /** @var string */
    protected $message = 'Action is forbidden.';
}
