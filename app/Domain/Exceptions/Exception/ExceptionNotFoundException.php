<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\Exception;

use App\Domain\Exceptions\NotFoundException;

final class ExceptionNotFoundException extends NotFoundException
{
    /** @var string */
    protected $message = 'Exception not found.';
}
