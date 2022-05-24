<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    // migrate database
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');

        // login
        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }

    public function test_users_screen_can_be_rendered()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
    }
}
