<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\ApiToken;
use App\Domain\Exceptions\User\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\Services\User\Registrar;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use PHPUnit\Framework\TestCase;

final class RegistrarTest extends TestCase
{
    private Registrar $registrar;
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private TokenGenerator $tokenGenerator;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
    }

    public function testFailedRegistration(): void
    {
        $this->userRepository->method('hasByEmail')->willReturn(true);
        $this->passwordHasher->method('hash')->willReturn('hash');
        $this->registrar = new Registrar($this->userRepository, $this->passwordHasher, $this->tokenGenerator);

        $this->expectException(UserAlreadyExistsException::class);

        $token = $this->registrar->register(
            new Name('testname'),
            new Email('test@test.com'),
            new CleanPassword('password')
        );
    }

    public function testSuccessRegistration(): void
    {
        $this->userRepository->method('hasByEmail')->willReturn(false);
        $this->passwordHasher->method('hash')->willReturn('hash');
        $this->tokenGenerator->method('generate')->willReturn('token');
        $this->registrar = new Registrar($this->userRepository, $this->passwordHasher, $this->tokenGenerator);

        $token = $this->registrar->register(
            new Name('testname'),
            new Email('test@test.com'),
            new CleanPassword('password')
        );

        $this->assertInstanceOf(ApiToken::class, $token);
    }
}
