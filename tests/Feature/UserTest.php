<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

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
}
