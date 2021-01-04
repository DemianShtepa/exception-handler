<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;

class User
{
    private int $id;
    private Name $name;
    private Email $email;
    private string $password;
    private ApiToken $apiToken;

    public function __construct(Name $name, Email $email, string $password, ApiToken $apiToken)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->apiToken = $apiToken;
        $this->apiToken->setUser($this);
    }

    public function getApiToken(): ApiToken
    {
        return $this->apiToken;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
