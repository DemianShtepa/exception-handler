<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\TestCase;

final class ResetPasswordControllerTest extends TestCase
{
    public function testRequestResetPasswordAndReturnOld(): void
    {
        $response = $this->post('/api/request-reset-password/exist@exist.com');

        $response->assertStatus(200);
    }

    public function testRequestResetPasswordAndCreateNew(): void
    {
        $response = $this->post('/api/request-reset-password/exist@exist.com1');

        $response->assertStatus(200);
    }

    public function testEmailNotExists(): void
    {
        $response = $this->post('/api/request-reset-password/not@exist.com');

        $response->assertStatus(404);
    }

    public function testTokenNotExists(): void
    {
        $response = $this->postJson('/api/reset-password/token1', [
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }

    public function testPasswordIsMissed(): void
    {
        $response = $this->post('/api/reset-password/token1',);

        $response->assertStatus(400);
    }

    public function testTokenIsMissed(): void
    {
        $response = $this->postJson('/api/reset-password', [
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }

    public function testSuccessResetPassword(): void
    {
        $response = $this->postJson('/api/reset-password/token', [
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }
}
