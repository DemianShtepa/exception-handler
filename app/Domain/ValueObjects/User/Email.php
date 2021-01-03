<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\User;

use Webmozart\Assert\Assert;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::email($value, 'Expected an email field to be a valid e-mail address');
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
