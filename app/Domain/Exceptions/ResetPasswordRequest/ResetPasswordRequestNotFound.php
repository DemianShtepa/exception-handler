<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\ResetPasswordRequest;

use App\Domain\Exceptions\NotFoundException;

final class ResetPasswordRequestNotFound extends NotFoundException
{
    /** @var string */
    protected $message = 'Reset password request not found.';
}
