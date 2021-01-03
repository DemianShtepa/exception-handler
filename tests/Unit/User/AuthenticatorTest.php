<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Exceptions\User\InvalidCredentialsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\User\Authenticator;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use DateInterval;
use DateTimeImmutable;
use Tests\TestCase;

final class AuthenticatorTest extends TestCase
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private TokenGenerator $tokenGenerator;
    private Authenticator $authenticator;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
    }

    public function testFailedCredentials()
    {
        $this->passwordHasher->method('check')->willReturn(false);
        $this->authenticator = new Authenticator($this->userRepository, $this->passwordHasher, $this->tokenGenerator);

        $this->expectException(InvalidCredentialsException::class);

        $token = $this->authenticator->login(new Email('mail@mail.com'), new CleanPassword('password'));
    }

    public function testReturnOldTokenIfNotExpired()
    {
        $token = new ApiToken('some-token', (new DateTimeImmutable())->add(new DateInterval('PT1H')));
        $this->passwordHasher->method('check')->willReturn(true);
        $this->userRepository->method('getByEmail')
            ->willReturn(new User(
                new Name('some name'),
                new Email('some@email.com'),
                'hashed-password',
                $token
            ));
        $this->authenticator = new Authenticator($this->userRepository, $this->passwordHasher, $this->tokenGenerator);

        $returnedToken = $this->authenticator->login(new Email('some@email.com'), new CleanPassword('password'));

        $this->assertEquals($token->getToken(), $returnedToken->getToken());
    }

    public function testReturnNewTokenIfExpired()
    {
        $token = new ApiToken('some-token', (new DateTimeImmutable())->sub(new DateInterval('PT1H')));
        $this->passwordHasher->method('check')->willReturn(true);
        $this->userRepository->method('getByEmail')
            ->willReturn(new User(
                new Name('some name'),
                new Email('some@email.com'),
                'hashed-password',
                $token
            ));
        $this->authenticator = new Authenticator($this->userRepository, $this->passwordHasher, $this->tokenGenerator);

        $returnedToken = $this->authenticator->login(new Email('some@email.com'), new CleanPassword('password'));

        $this->assertNotEquals($token->getToken(), $returnedToken->getToken());
    }
}
