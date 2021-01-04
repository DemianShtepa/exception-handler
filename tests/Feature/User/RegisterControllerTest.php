<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\TestCase;

final class RegisterControllerTest extends TestCase
{
    public function testFailedRegistration(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Exist',
            'email' => 'exist@exist.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function testSuccessRegistration(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New',
            'email' => 'new@mail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
    }

    public function testFailedName(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '1',
            'email' => 'new@mail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(400);
    }

    public function testFailedEmail(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New',
            'email' => 'mail.com',
            'password' => 'passw'
        ]);

        $response->assertStatus(400);
    }

    public function testFailedPassword(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New',
            'email' => 'mail@mail.com',
            'password' => 'pass'
        ]);

        $response->assertStatus(400);
    }
}
