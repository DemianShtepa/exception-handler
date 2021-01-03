<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Token;

use App\Domain\Services\Token\Interfaces\TokenGenerator as TokenGeneratorInterface;
use Ramsey\Uuid\Uuid;

final class TokenGenerator implements TokenGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
