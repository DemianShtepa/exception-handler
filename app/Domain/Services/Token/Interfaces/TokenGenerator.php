<?php

declare(strict_types=1);

namespace App\Domain\Services\Token\Interfaces;

interface TokenGenerator
{
    public function generate(): string;
}
