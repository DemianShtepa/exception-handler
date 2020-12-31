<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use DomainException;

final class UserAlreadyExistsException extends DomainException
{
    /** @var string  */
    protected $message = 'User already exists.';
}
