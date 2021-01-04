<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\ResetPasswordRequest;

use DomainException;

final class ResetPasswordRequestExpiredException extends DomainException
{
    /** @var string */
    protected $message = 'Token is expired.';
}
