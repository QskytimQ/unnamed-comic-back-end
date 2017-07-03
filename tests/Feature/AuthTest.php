<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    public function testRegister()
    {
        $response = $this->json('POST', '/api/auth/register');
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/auth/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'secret',
        ]);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/auth/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'secret',
        ]);
        $response->assertStatus(400);
    }

    public function testAuth()
    {
        $response = $this->json('POST', '/api/auth');
        $response->assertStatus(401);

        $response = $this->json('POST', '/api/auth', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $response->assertStatus(401);

        $response = $this->json('POST', '/api/auth', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $response->assertStatus(401);

        $response = $this->json('POST', '/api/auth', [
            'email' => 'test@test.com',
            'password' => 'secret',
        ]);
        $response->assertStatus(200);
    }
}