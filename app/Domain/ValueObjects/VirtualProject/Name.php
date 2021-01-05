<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\VirtualProject;

use Webmozart\Assert\Assert;

final class Name
{
    private string $value;
    private const MAX_NAME_SIZE = 36;
    private const MIN_NAME_SIZE = 3;

    public function __construct(string $value)
    {
        Assert::lengthBetween(
            $value,
            self::MIN_NAME_SIZE,
            self::MAX_NAME_SIZE,
            'Expected a name field to contain between '
            . self::MIN_NAME_SIZE . ' and '
            . self::MAX_NAME_SIZE . ' characters'
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
