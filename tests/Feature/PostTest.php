<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;

use App\Services\PostService;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{


    use RefreshDatabase;
    // use DatabaseTransactions;

    // protected function tearDown(): void
    // {
    //     Post::where('title', 'LIKE', 'Test%')->delete();
    //     parent::tearDown();
    // }



    // public function test_it_stores_a_new_post()
    // {
    //     // Create a user and authenticate
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     // Define post data
    //     $post = Post::create([
    //         'title'   => 'Test Post',
    //         'slug'    => 'test-post',
    //         'content' => 'This is a test post content.',
    //         'image'   => null,
    //         'user_id' => $user->id,
    //     ]);

    //     $this->assertNotNull($post); // Ensure redirection
    //     $this->assertEquals('Test Post',$post->title); // Ensure redirection

    // }

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
        $response = $this->post(route('posts.store'), $postData);

        // Ensure the post exists in the database
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);

        // Ensure the response is a redirect (assuming store() redirects)
        $response->assertRedirect();
    }


    public function test_it_updates_a_post()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $updatedData = [
            'title'   => 'Updated Post Title',
            'slug'    => 'updated-post',
            'content' => 'Updated content.',
        ];

        // Send PUT request to update the post
        $response = $this->put(route('posts.update', $post->slug), $updatedData);

        // ✅ Ensure the post was updated in the database
        $this->assertDatabaseHas('posts', ['title' => 'Updated Post Title']);

        // ✅ Refresh the post to get updated values
        $post->refresh();

        // ✅ Assert that the title has been updated
        $this->assertEquals('Updated Post Title', $post->title);

        // ✅ Ensure the response redirects (assuming update redirects)
        $response->assertRedirect(route('posts.index'));
    }


    public function test_it_deletes_a_post()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        // ❌ Fix: Pass the slug, not the ID
        $response = $this->delete(route('posts.destroy', $post->slug));

        // ❌ Fix: Use assertSoftDeleted if SoftDeletes is enabled
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($post))) {
            $this->assertSoftDeleted('posts', ['id' => $post->id]);
        } else {
            $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        }

        // ✅ Ensure redirection
        $response->assertRedirect(route('posts.index'));
    }


    public function test_it_shows_a_post()
    {
        $this->withoutExceptionHandling(); // To see detailed errors

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }



    public function test_it_lists_all_posts()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $this->assertCount(3, Post::all());
    }
}
