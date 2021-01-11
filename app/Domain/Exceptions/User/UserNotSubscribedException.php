<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use DomainException;

final class UserNotSubscribedException extends DomainException
{
    /** @var string  */
    protected $message = 'User is not subscribed to virtual project.';
}
