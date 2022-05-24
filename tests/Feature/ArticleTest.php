<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Generator as Faker;
use Tests\TestCase;

class ArticleTest extends TestCase
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

    public function test_articles_screen_can_be_rendered()
    {
        $response = $this->get('/articles');
        $response->assertStatus(200);
    }

    public function test_form_create_article_screen_can_be_rendered()
    {
        $response = $this->get('/articles/create');
        $response->assertStatus(200);
    }

    public function test_user_can_create_an_article()
    {
        $response = $this->post('/articles', [
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

        $response = $this->get('/articles');
        $response->assertStatus(200);
    }

    public function test_form_edit_article_screen_can_be_rendered()
    {
        $response = $this->get('/articles/1/edit');
        $response->assertStatus(200);
    }

    public function test_user_can_edit_an_article()
    {
        $id = 1;
        $oldTitle = Article::find($id)->image;

        $response = $this->put('/articles/' . $id, [
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

        $response = $this->get('/articles');
        $response->assertStatus(200);
    }

    public function test_user_can_delete_an_article()
    {
        $id = 1;
        $response = $this->delete('/articles/' . $id);

        $this->assertDatabaseMissing('articles', [
            'id' => $id,
        ]);

        $response = $this->get('/articles');
        $response->assertStatus(200);
    }
}
