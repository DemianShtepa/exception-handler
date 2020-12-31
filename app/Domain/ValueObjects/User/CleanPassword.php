<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\User;

use Webmozart\Assert\Assert;

final class CleanPassword
{
    private string $value;
    private const PASSWORD_SIZE = 8;

    public function __construct(string $value)
    {
        Assert::minLength($value, self::PASSWORD_SIZE);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
