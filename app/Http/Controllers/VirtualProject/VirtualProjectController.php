<?php

declare(strict_types=1);

namespace App\Http\Controllers\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\VirtualProject\VirtualProjectService;
use App\Domain\ValueObjects\VirtualProject\Name;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class VirtualProjectController
{
    private VirtualProjectService $virtualProjectService;

    public function __construct(VirtualProjectService $virtualProjectService)
    {
        $this->virtualProjectService = $virtualProjectService;
    }

    public function create(Request $request): JsonResponse
    {
        $this->virtualProjectService->createVirtualProject(
            new Name($request->get('name', '')),
            $request->user()
        );
        return new JsonResponse([
            'message' => 'Project created.'
        ], Response::HTTP_CREATED);
    }

    public function getAll(Request $request): JsonResponse
    {
        $projects = array_map(function (VirtualProject $virtualProject) {
            return [
                'name' => $virtualProject->getName()->getValue(),
                'push_token' => $virtualProject->getPushToken(),
                'invite_token' => $virtualProject->getInviteToken(),
                'created_at' => $virtualProject->getCreatedAt()
            ];
        }, $request->user()->getVirtualProjects()->toArray());

        return new JsonResponse($projects);
    }

    public function subscribe(Request $request, string $inviteToken): JsonResponse
    {
        $this->virtualProjectService->subscribe($request->user(), $inviteToken);

        return new JsonResponse([
            'message' => 'User subscribed.'
        ]);
    }
}
