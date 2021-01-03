<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\TestCase;

final class LoginControllerTest extends TestCase
{
    public function testUserNotFoundLogin(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'not@exist.com',
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }

    public function testInvalidCredentialsLogin(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'exist@exist.com',
            'password' => 'password1'
        ]);

        $response->assertStatus(422);
    }

    public function testSuccessLogin(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'exist@exist.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }
}
