<?php

declare(strict_types=1);

namespace App\Domain\Services\Exception;

use App\Domain\Entities\Exception;
use App\Domain\Repositories\ExceptionRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\ValueObjects\Exception\Name;
use App\Domain\ValueObjects\Exception\Stacktrace;
use DateTimeImmutable;

final class ExceptionService
{
    private ExceptionRepository $exceptionRepository;

    private VirtualProjectRepository $virtualProjectRepository;

    public function __construct(
        ExceptionRepository $exceptionRepository,
        VirtualProjectRepository $virtualProjectRepository
    ) {
        $this->exceptionRepository = $exceptionRepository;
        $this->virtualProjectRepository = $virtualProjectRepository;
    }

    public function createException(string $pushToken, string $name, string $stackTrace): Exception
    {
        $project = $this->virtualProjectRepository->getByPushToken($pushToken);

        $exception = new Exception(new Name($name), new Stacktrace($stackTrace), new DateTimeImmutable(), $project);
        $this->exceptionRepository->save($exception);

        return $exception;
    }
}
