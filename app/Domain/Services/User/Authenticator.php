<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Entities\ApiToken;
use App\Domain\Exceptions\User\InvalidCredentialsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use DateInterval;
use DateTimeImmutable;

final class Authenticator
{
    private UserRepository $userRepository;

    private PasswordHasher $passwordHasher;

    private TokenGenerator $tokenGenerator;

    public function __construct(
        UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        TokenGenerator $tokenGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function login(string $email, string $cleanPassword): ApiToken
    {
        $email = new Email($email);
        $cleanPassword = new CleanPassword($cleanPassword);
        $user = $this->userRepository->getByEmail($email);
        $token = $user->getApiToken();

        if (!$this->passwordHasher->check($cleanPassword, $user->getPassword())) {
            throw new InvalidCredentialsException();
        }

        if ($token->isExpiredComparedTo(new DateTimeImmutable())) {
            $token->setToken($this->tokenGenerator->generate());
            $token->setExpiresAt((new DateTimeImmutable())->add(new DateInterval('P5D')));

            $this->userRepository->save($user);
        }

        return $token;
    }
}
