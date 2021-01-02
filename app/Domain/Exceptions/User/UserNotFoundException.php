<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\User;

use App\Domain\Exceptions\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    /** @var string  */
    protected $message = 'User not found.';
}
