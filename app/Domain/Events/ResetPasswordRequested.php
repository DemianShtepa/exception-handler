<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Events\Interfaces\Event;

final class ResetPasswordRequested implements Event
{
    private string $email;
    private string $token;


    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }
}
