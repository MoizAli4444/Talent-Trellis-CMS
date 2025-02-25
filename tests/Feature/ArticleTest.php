<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;

use App\Services\ArticleService;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticleTest extends TestCase
{


    use RefreshDatabase;
   

    public function test_it_stores_a_new_post()
    {
        $this->withoutMiddleware();  
        $this->withoutExceptionHandling();


        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title'   => 'Test Post',
            'slug'    => 'test-post',
            'content' => 'This is a test post content.',
            'image'   => null,
            'user_id' => $user->id,
        ];

        // Send a POST request to the store method
        $response = $this->post(route('articles.store'), $postData);

        // Ensure the post exists in the database
        $this->assertDatabaseHas('articles', ['title' => 'Test Post']);

        // Ensure the response is a redirect (assuming store() redirects)
        $response->assertRedirect();
    }


    public function test_it_updates_a_post()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Article::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $updatedData = [
            'title'   => 'Updated Post Title',
            'slug'    => 'updated-post',
            'content' => 'Updated content.',
        ];

        // Send PUT request to update the post
        $response = $this->put(route('articles.update', $post->slug), $updatedData);

        // ✅ Ensure the post was updated in the database
        $this->assertDatabaseHas('articles', ['title' => 'Updated Post Title']);

        // ✅ Refresh the post to get updated values
        $post->refresh();

        // ✅ Assert that the title has been updated
        $this->assertEquals('Updated Post Title', $post->title);

        // ✅ Ensure the response redirects (assuming update redirects)
        $response->assertRedirect(route('articles.index'));
    }


    public function test_it_deletes_a_post()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Article::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        // ❌ Fix: Pass the slug, not the ID
        $response = $this->delete(route('articles.destroy', $post->slug));

        // ❌ Fix: Use assertSoftDeleted if SoftDeletes is enabled
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($post))) {
            $this->assertSoftDeleted('articles', ['id' => $post->id]);
        } else {
            $this->assertDatabaseMissing('articles', ['id' => $post->id]);
        }

        // ✅ Ensure redirection
        $response->assertRedirect(route('articles.index'));
    }


    public function test_it_shows_a_article()
    {
        $this->withoutExceptionHandling(); // To see detailed errors

        $user = User::factory()->create();
        $post = Article::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('articles.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }



    public function test_it_lists_all_articles()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Article::factory()->count(3)->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('articles.index'));

        $response->assertStatus(200);
        $this->assertCount(3, Article::all());
    }
}
