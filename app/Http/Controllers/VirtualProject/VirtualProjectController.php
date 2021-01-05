<?php

declare(strict_types=1);

namespace App\Http\Controllers\VirtualProject;

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

    public function create(Request $request)
    {
        $this->virtualProjectService->createVirtualProject(
            new Name($request->get('name', '')),
            $request->user()
        );
        return new JsonResponse([
            'message' => 'Project created.'
        ], Response::HTTP_CREATED);
    }
}
