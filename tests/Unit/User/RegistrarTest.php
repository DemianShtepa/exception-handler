<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\Services\User\Registrar;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use PHPUnit\Framework\TestCase;

class RegistrarTest extends TestCase
{
    private Registrar $registrar;
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);
    }

    public function testFailedRegistration(): void
    {
        $this->userRepository->method('hasByEmail')->willReturn(true);
        $this->passwordHasher->method('hash')->willReturn('hash');
        $this->registrar = new Registrar($this->userRepository, $this->passwordHasher);

        $this->expectException(UserAlreadyExistsException::class);

        $user = $this->registrar->register(
            new Name('testname'),
            new Email('test@test.com'),
            new CleanPassword('password')
        );
    }

    public function testSuccessRegistration(): void
    {
        $this->userRepository->method('hasByEmail')->willReturn(false);
        $this->passwordHasher->method('hash')->willReturn('hash');
        $this->registrar = new Registrar($this->userRepository, $this->passwordHasher);

        $user = $this->registrar->register(
            new Name('testname'),
            new Email('test@test.com'),
            new CleanPassword('password')
        );

        $this->assertInstanceOf(User::class, $user);
    }
}
