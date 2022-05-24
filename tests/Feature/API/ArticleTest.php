<?php

namespace Tests\Feature\API;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ArticleTest extends TestCase
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

    public function test_user_can_get_the_whole_article()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/v1/articles');

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_create_an_article()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/v1/articles', [
            'title' => "Cara Membuat Unit Testing",
            'content' => "Pertama install Laravel terlebih dahulu",
            'image' => "https://miro.medium.com/max/1400/1*zgYKTRI7Q270sPnsRVkDkw.png",
            'category_id' => 2,
        ]);
        $this->assertDatabaseHas('articles', [
            'title' => "Cara Membuat Unit Testing",
            'content' => "Pertama install Laravel terlebih dahulu",
            'image' => "https://miro.medium.com/max/1400/1*zgYKTRI7Q270sPnsRVkDkw.png",
            'category_id' => 2,
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_search_for_an_article()
    {
        $id = 1;

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/v1/articles/' . $id);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_edit_an_article()
    {
        $id = 1;
        $oldTitle = Article::find($id)->image;

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/v1/articles/' . $id, [
            'title' => "Cara Membuat Unit Testing di Laravel",
            'content' => Article::find($id)->content,
            'image' => Article::find($id)->image,
            'user_id' => Article::find($id)->user_id,
            'category_id' => Article::find($id)->category_id,
        ]);

        $this->assertEquals("Cara Membuat Unit Testing di Laravel", Article::find($id)->title);
        $this->assertDatabaseHas('articles', [
            'title' => "Cara Membuat Unit Testing di Laravel",
        ]);
        $this->assertDatabaseMissing('articles', [
            'title' => $oldTitle,
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }

    public function test_user_can_delete_an_article()
    {
        $id = 1;
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/v1/articles/' . $id);

        $this->assertDatabaseMissing('articles', [
            'id' => $id,
        ]);

        $this->assertTrue($response['success']);
        $response->assertStatus(200);
    }
}
