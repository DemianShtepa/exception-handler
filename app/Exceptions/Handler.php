<?php

namespace App\Exceptions;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\ForbiddenException;
use DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        InvalidArgumentException::class,
        ForbiddenException::class,
        DomainException::class,
        NotFoundException::class
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = ['message' => $e->getMessage()];

        if ($e instanceof InvalidArgumentException) {
            $response['status_code'] = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        }

        if ($e instanceof ForbiddenException) {
            $response['status_code'] = Response::HTTP_FORBIDDEN;
            return new JsonResponse($response, Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof DomainException) {
            $response['status_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            return new JsonResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof NotFoundException) {
            $response['status_code'] = Response::HTTP_NOT_FOUND;
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }
}
