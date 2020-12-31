<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\User;

use Webmozart\Assert\Assert;

final class Name
{
    private string $value;
    private const MAX_NAME_SIZE = 255;
    private const MIN_NAME_SIZE = 3;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, self::MIN_NAME_SIZE, self::MAX_NAME_SIZE);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
