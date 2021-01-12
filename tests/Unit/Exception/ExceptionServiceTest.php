<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use App\Domain\Repositories\ExceptionRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Exception\ExceptionService;
use Tests\TestCase;

class ExceptionServiceTest extends TestCase
{
    private ExceptionRepository $exceptionRepository;
    private VirtualProjectRepository $virtualProjectRepository;
    private ExceptionService $exceptionService;

    public function setUp(): void
    {
        $this->exceptionRepository = $this->createMock(ExceptionRepository::class);
        $this->virtualProjectRepository = $this->createMock(VirtualProjectRepository::class);
    }

    public function testSuccessfulExceptionCreation(): void
    {
        $this->exceptionService = new ExceptionService($this->exceptionRepository, $this->virtualProjectRepository);

        $exception = $this->exceptionService->createException('push-token', 'name', 'stacktrace');

        $this->assertSame('name', $exception->getName()->getValue());
        $this->assertSame('stacktrace', $exception->getStacktrace()->getValue());
    }
}
