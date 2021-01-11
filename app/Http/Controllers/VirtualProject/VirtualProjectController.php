<?php

declare(strict_types=1);

namespace App\Http\Controllers\VirtualProject;

use App\Domain\Entities\VirtualProject;
use App\Domain\Services\VirtualProject\UserSubscriptionManager;
use App\Domain\Services\VirtualProject\VirtualProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class VirtualProjectController
{
    private VirtualProjectService $virtualProjectService;

    private UserSubscriptionManager $userSubscriptionManager;

    public function __construct(
        VirtualProjectService $virtualProjectService,
        UserSubscriptionManager $userSubscriptionManager
    ) {
        $this->virtualProjectService = $virtualProjectService;
        $this->userSubscriptionManager = $userSubscriptionManager;
    }

    public function create(Request $request): JsonResponse
    {
        $this->virtualProjectService->createVirtualProject(
            $request->get('name', ''),
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

    public function updateName(Request $request, string $id): JsonResponse
    {
        $name = $this->virtualProjectService
            ->updateName($request->user(), $request->get('name', ''), (int)$id);

        return new JsonResponse(
            ['name' => $name]
        );
    }

    public function changeInviteToken(Request $request, string $id): JsonResponse
    {
        $token = $this->virtualProjectService->changeInviteToken($request->user(), (int)$id);

        return new JsonResponse([
            'invite_token' => $token
        ]);
    }

    public function changePushToken(Request $request, string $id): JsonResponse
    {
        $token = $this->virtualProjectService->changePushToken($request->user(), (int)$id);

        return new JsonResponse(
            ['push_token' => $token]
        );
    }


    public function subscribe(Request $request, string $inviteToken): JsonResponse
    {
        $this->userSubscriptionManager->subscribe($request->user(), $inviteToken);

        return new JsonResponse([
            'message' => 'User subscribed.'
        ]);
    }

    public function unsubscribe(Request $request, string $id): JsonResponse
    {
        $this->userSubscriptionManager->unsubscribe($request->user(), (int)$id);

        return new JsonResponse([
            'message' => 'User unsubscribed.'
        ]);
    }
}
