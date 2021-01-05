<?php

declare(strict_types=1);

namespace Tests\Feature\VirtualProject;

use Tests\TestCase;

class VirtualProjectControllerTest extends TestCase
{
    public function testSuccessVirtualProjectCreation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->postJson('/api/virtual-project', [
                'name' => 'some name'
            ]);

        $response->assertStatus(201);
    }

    public function testNameFieldMissed(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->postJson('/api/virtual-project', []);

        $response->assertStatus(400);
    }

    public function testSuccessGettingAllProjects(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->get('/api/virtual-projects');

        $response->assertStatus(200);
    }
}
