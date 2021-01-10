<?php

declare(strict_types=1);

namespace Tests\Feature\VirtualProject;

use Tests\TestCase;

class VirtualProjectControllerTest extends TestCase
{
    public function testSuccessVirtualProjectCreation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->postJson('/api/virtual-projects', [
                'name' => 'some name'
            ]);

        $response->assertStatus(201);
    }

    public function testNameFieldMissed(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->postJson('/api/virtual-projects', []);

        $response->assertStatus(400);
    }

    public function testSuccessGettingAllProjects(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->get('/api/virtual-projects');

        $response->assertStatus(200);
    }

    public function testSuccessfulSubscribing(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->post('/api/virtual-projects/token/subscribe');

        $response->assertStatus(200);
    }

    public function testVirtualProjectNotFoundOnSubscribing(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->post('/api/virtual-projects/token1/subscribe');

        $response->assertStatus(404);
    }

    public function testSuccessVirtualProjectChangeName(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->putJson('/api/virtual-projects/1/update-name', ['name' => 'name']);

        $response->assertStatus(200);
    }

    public function testVirtualProjectNotFoundOnNameChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->putJson('/api/virtual-projects/token1/update-name', ['name' => 'name']);

        $response->assertStatus(404);
    }

    public function testPermissionDeniedOnNameChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->putJson('/api/virtual-projects/1/update-name', ['name' => 'name']);

        $response->assertStatus(403);
    }

    public function testSuccessVirtualProjectPushTokenChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->put('/api/virtual-projects/1/change-push-token');

        $response->assertStatus(200);
    }

    public function testVirtualProjectNotFoundOnPushTokenChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->put('/api/virtual-projects/token1/change-push-token');

        $response->assertStatus(404);
    }

    public function testPermissionDeniedOnPushTokenUpdating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->put('/api/virtual-projects/1/update-name');

        $response->assertStatus(403);
    }

    public function testSuccessVirtualProjectInviteTokenChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->put('/api/virtual-projects/1/change-invite-token');

        $response->assertStatus(200);
    }

    public function testVirtualProjectNotFoundOnInviteTokenChanging(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->put('/api/virtual-projects/token1/change-invite-token');

        $response->assertStatus(404);
    }

    public function testPermissionDeniedOnInviteTokenUpdating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token1')
            ->put('/api/virtual-projects/1/change-invite-token');

        $response->assertStatus(403);
    }
}
