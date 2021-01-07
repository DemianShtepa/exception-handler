<?php

namespace App\Exceptions;

use App\Domain\Exceptions\NotFoundException;
use DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
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
