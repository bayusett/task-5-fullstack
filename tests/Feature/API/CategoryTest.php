<?php

namespace Tests\Feature\API;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');

        Artisan::call('passport:install');
    }

    protected function authenticate()
    {
        // Simulated landing
        $response = $this->json('POST', '/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        return $response['token'];
    }

    public function test_user_can_get_the_whole_category()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/v1/categories');

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_create_an_category()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/v1/categories', [
            'name' => "Tutorial",
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => "Tutorial",
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_search_for_an_category()
    {
        $id = 1;

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/v1/categories/' . $id);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_edit_an_category()
    {
        $id = 1;
        $oldName = Category::find($id)->name;

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/v1/categories/' . $id, [
            'name' => "Laravel",
            'user_id' => Category::find($id)->user_id,
        ]);

        $this->assertEquals("Laravel", Category::find($id)->name);
        $this->assertDatabaseHas('categories', [
            'name' => "Laravel",
        ]);
        $this->assertDatabaseMissing('categories', [
            'name' => $oldName,
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_delete_an_category()
    {
        $id = 1;
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/v1/categories/' . $id);

        $this->assertDatabaseMissing('categories', [
            'id' => $id,
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }
}
