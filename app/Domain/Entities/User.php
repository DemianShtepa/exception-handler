<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;

final class User
{
    private int $id;
    private Name $name;
    private Email $email;
    private string $password;

    public function __construct(Name $name, Email $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
