<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\VirtualProject;

use App\Domain\Exceptions\NotFoundException;

final class VirtualProjectNotFoundException extends NotFoundException
{
    /** @var string */
    protected $message = 'Virtual project not found.';
}
