<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function testFailedRegistration(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Exist',
            'email' => 'exist@exist.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function testSuccessRegistration(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'New',
            'email' => 'new@mail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
    }
}
