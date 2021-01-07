<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use DomainException;

final class UserAlreadySubscribedException extends DomainException
{
    /** @var string  */
    protected $message = 'User already subscribed to virtual project.';
}
