<?php

declare(strict_types=1);

namespace App\Http\Controllers\Exception;

use App\Domain\Services\Exception\ExceptionService;
use App\Domain\Services\Exception\ExceptionStatusManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ExceptionController
{
    private ExceptionService $exceptionService;

    private ExceptionStatusManager $exceptionStatusManager;

    public function __construct(ExceptionService $exceptionService, ExceptionStatusManager $exceptionStatusManager)
    {
        $this->exceptionService = $exceptionService;
        $this->exceptionStatusManager = $exceptionStatusManager;
    }

    public function create(Request $request, string $pushToken): JsonResponse
    {
        $this->exceptionService
            ->createException($pushToken, $request->get('name', ''), $request->get('stacktrace', ''));

        return new JsonResponse();
    }

    public function setToUnhandled(Request $request, string $exceptionId): JsonResponse
    {
        $this->exceptionStatusManager->setToUnhandled($request->user(), (int)$exceptionId);

        return new JsonResponse();
    }

    public function setToHandled(Request $request, string $exceptionId): JsonResponse
    {
        $this->exceptionStatusManager->setToHandled($request->user(), (int)$exceptionId);

        return new JsonResponse();
    }

    public function setToSolved(Request $request, string $exceptionId): JsonResponse
    {
        $this->exceptionStatusManager->setToSolved($request->user(), (int)$exceptionId);

        return new JsonResponse();
    }
}
