<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_categories_screen_can_be_rendered()
    {
        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    public function test_form_create_category_screen_can_be_rendered()
    {
        $response = $this->get('/categories/create');
        $response->assertStatus(200);
    }

    public function test_user_can_create_an_category()
    {
        $response = $this->post('/categories', [
            'name' => "Tutorial",
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => "Tutorial",
        ]);

        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    public function test_form_edit_category_screen_can_be_rendered()
    {
        $response = $this->get('/categories/1/edit');
        $response->assertStatus(200);
    }

    public function test_user_can_edit_an_category()
    {
        $id = 1;
        $oldName = Category::find($id)->name;

        $response = $this->put('/categories/' . $id, [
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

        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    public function test_user_can_delete_an_category()
    {
        $id = 1;
        $response = $this->delete('/categories/' . $id);

        $this->assertDatabaseMissing('categories', [
            'id' => $id,
        ]);

        $response = $this->get('/categories');
        $response->assertStatus(200);
    }
}
