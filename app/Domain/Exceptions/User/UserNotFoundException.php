<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use App\Domain\Exceptions\ModelNotFoundException;

final class UserNotFoundException extends ModelNotFoundException
{
    /** @var string  */
    protected $message = 'User not found.';
}
