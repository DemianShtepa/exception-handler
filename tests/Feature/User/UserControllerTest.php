<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\TestCase;

final class UserControllerTest extends TestCase
{
    public function testSuccessGettingUser(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer token')
            ->postJson('/api/user');

        $response->assertStatus(200);
    }

    public function testFailedGettingUser(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/api/user');

        $response->assertStatus(401);
    }
}
