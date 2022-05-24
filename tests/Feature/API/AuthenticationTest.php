<?php

namespace Tests\Feature\API;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
    }

    public function test_new_user_get_token_from_register()
    {
        $response = $this->json('POST', '/api/v1/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Receive our token
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_user_get_token_from_login()
    {
        // Creating Users
        $user = User::factory()->create();

        // Simulated landing
        $response = $this->json('POST', '/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Determine whether the login is successful and receive token
        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_user_not_getting_token_from_failed_login()
    {
        // Creating Users
        $user = User::factory()->create();

        // Simulated landing
        $response = $this->json('POST', '/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "Password mismatch",
            ]);
    }

    public function test_user_can_logout_when_already_login()
    {
        $user = User::factory()->create();

        // Simulated landing
        $response = $this->json('POST', '/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $token =  $response['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/v1/logout');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'You have been successfully logged out!',
            ]);
    }
}
