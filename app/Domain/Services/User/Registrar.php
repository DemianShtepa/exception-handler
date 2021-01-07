<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use DateInterval;
use DateTimeImmutable;

final class Registrar
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

    public function register(Name $name, Email $email, CleanPassword $cleanPassword): ApiToken
    {
        if ($this->userRepository->hasByEmail($email)) {
            throw new UserAlreadyExistsException();
        }

        $apiToken = new ApiToken(
            $this->tokenGenerator->generate(),
            (new DateTimeImmutable())->add(new DateInterval('P5D'))
        );
        $user = new User($name, $email, $this->passwordHasher->hash($cleanPassword), $apiToken);
        $this->userRepository->save($user);

        return $apiToken;
    }
}
