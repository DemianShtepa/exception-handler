<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileJsonResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (
            $response instanceof JsonResponse &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled()
        ) {
            $response->setData($response->getData(true) + [
                    '_debugbar' => app('debugbar')->getData(),
                ]);
        }

        return $response;
    }
}
