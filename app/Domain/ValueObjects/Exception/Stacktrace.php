<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\Exception;

use Webmozart\Assert\Assert;

final class Stacktrace
{
    private const MAX_STACKTRACE_SIZE = 65535;
    private const MIN_STACKTRACE_SIZE = 3;
    private string $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween(
            $value,
            self::MIN_STACKTRACE_SIZE,
            self::MAX_STACKTRACE_SIZE,
            'Expected a stacktrace field to contain between '
            . self::MIN_STACKTRACE_SIZE . ' and '
            . self::MAX_STACKTRACE_SIZE . ' characters'
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
