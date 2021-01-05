<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Events\Interfaces\Event;
use App\Domain\ValueObjects\User\Email;

final class ResetPasswordRequested implements Event
{
    private Email $email;
    private string $token;


    public function __construct(Email $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
