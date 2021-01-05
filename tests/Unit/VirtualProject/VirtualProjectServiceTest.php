<?php

declare(strict_types=1);

namespace Tests\Unit\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\VirtualProject\VirtualProjectService;
use App\Domain\ValueObjects\VirtualProject\Name;
use Tests\TestCase;

class VirtualProjectServiceTest extends TestCase
{
    private VirtualProjectRepository $virtualProjectRepository;
    private TokenGenerator $tokenGenerator;
    private VirtualProjectService $virtualProjectService;

    protected function setUp(): void
    {
        $this->virtualProjectRepository = $this->createMock(VirtualProjectRepository::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
    }

    public function testSuccessProjectCreation(): void
    {
        $this->tokenGenerator->method('generate')->willReturn('token');
        $this->virtualProjectService = new VirtualProjectService($this->virtualProjectRepository, $this->tokenGenerator);

        $project = $this->virtualProjectService->createVirtualProject(
          new Name('somename'),
          $this->createMock(User::class)
        );

        $this->assertEquals('somename', $project->getName()->getValue());
        $this->assertEquals('token', $project->getInviteToken());
        $this->assertEquals('token', $project->getPushToken());
    }
}
