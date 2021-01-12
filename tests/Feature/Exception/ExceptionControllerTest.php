<?php

declare(strict_types=1);

namespace Tests\Feature\Exception;

use Tests\TestCase;

class ExceptionControllerTest extends TestCase
{
    public function testSuccessCreateException(): void
    {
        $response = $this->postJson('/api/exceptions/token', [
            'name' => 'name',
            'stacktrace' => 'stacktrace'
        ]);

        $response->assertStatus(200);
    }

    public function testVirtualProjectNotFoundOnCreation(): void
    {
        $response = $this->postJson('/api/exceptions/token1', [
            'name' => 'name',
            'stacktrace' => 'stacktrace'
        ]);

        $response->assertStatus(404);
    }

    public function testFailedNameOnCreation(): void
    {
        $response = $this->postJson('/api/exceptions/token', [
            'stacktrace' => 'stacktrace'
        ]);

        $response->assertStatus(400);
    }

    public function testFailedStacktraceOnCreation(): void
    {
        $response = $this->postJson('/api/exceptions/token', [
            'name' => 'name',
        ]);

        $response->assertStatus(400);
    }

    public function testFailedSetToUnhandledFromUnhandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/1/unhandle');

        $response->assertStatus(422);
    }

    public function testFailedSetToSolvedFromUnhandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/1/solve');

        $response->assertStatus(422);
    }

    public function testSuccessSetToHandledFromUnhandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/1/handle');

        $response->assertStatus(200);
    }

    public function testSuccessSetToSolvedFromUnhandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/1/solve');

        $response->assertStatus(200);
    }

    public function testFailedSetToHandledFromHandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/2/handle');

        $response->assertStatus(422);
    }

    public function testSuccessSetToUnhandledFromHandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/2/unhandle');

        $response->assertStatus(200);
    }

    public function testFailedSetToSolveFromSolved(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/3/solve');

        $response->assertStatus(422);
    }

    public function testFailedSetToUnhandleFromSolved(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/3/unhandle');

        $response->assertStatus(422);
    }

    public function testSuccessSetToHandleFromSolved(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token3')
            ->put('/api/exceptions/3/handle');

        $response->assertStatus(200);
    }

    public function testPermissionDeniedOnUnhandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->put('/api/exceptions/1/unhandle');

        $response->assertStatus(403);
    }

    public function testPermissionDeniedOnHandled(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->put('/api/exceptions/1/handle');

        $response->assertStatus(403);
    }

    public function testPermissionDeniedOnSolved(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->put('/api/exceptions/1/solve');

        $response->assertStatus(403);
    }
}
