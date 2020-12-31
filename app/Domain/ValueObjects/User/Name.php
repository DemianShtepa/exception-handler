<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\User;

use Webmozart\Assert\Assert;

final class Name
{
    private string $value;
    private const NAME_SIZE = 255;

    public function __construct(string $value)
    {
        Assert::maxLength($value, self::NAME_SIZE);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
