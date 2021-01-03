<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use DomainException;

final class InvalidCredentialsException extends DomainException
{
    /** @var string  */
    protected $message = 'Invalid credentials given were provided.';
}
