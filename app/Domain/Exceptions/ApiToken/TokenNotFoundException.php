<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\ApiToken;

use App\Domain\Exceptions\NotFoundException;

final class TokenNotFoundException extends NotFoundException
{
    /** @var string */
    protected $message = 'Token not found.';
}
