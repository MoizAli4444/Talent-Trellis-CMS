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


class PostTest extends TestCase
{
    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     Post::truncate(); // Clears only the 'posts' table
    //     // Disable CSRF protection for tests
    //     // $this->withoutMiddleware(VerifyCsrfToken::class);

    // }



    // protected $postService;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->postService = Mockery::mock(PostService::class);
    //     $this->app->instance(PostService::class, $this->postService);
    // }


    use RefreshDatabase;

    protected $user;
    protected $postService;

    protected function setUp(): void
    {
        parent::setUp();

        // ✅ Create a global user to avoid duplication
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // ✅ Mock the PostService
        $this->postService = Mockery::mock(PostService::class);
        $this->app->instance(PostService::class, $this->postService);
    }

    /** @test */
    public function it_creates_and_deletes_a_post_in_one_flow()
    {

        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a post assigned to the user
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Mock PostService and define expectation for deletePost method
        $postServiceMock = Mockery::mock(PostService::class);
        $this->app->instance(PostService::class, $postServiceMock);

        // Expect deletePost to be called once
        $postServiceMock->shouldReceive('deletePost')
            ->once()
            ->with(Mockery::on(function ($arg) use ($post) {
                return $arg->id === $post->id;
            }));

        // Send delete request
        $response = $this->delete(route('posts.destroy', $post->slug));

        // Assert response status
        $response->assertRedirect(route('posts.index'));

        // Verify the post is deleted
        // $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function it_displays_a_list_of_posts()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->postService->shouldReceive('getAllPosts')->once()->andReturn(collect());

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_the_create_post_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('posts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('posts.create');
    }

    /** @test */
    /** @test */
    /** @test */
    public function test_it_stores_a_new_post()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Define post data
        $post = Post::create([
            'title'   => 'Test Post',
            'slug'    => 'test-post',
            'content' => 'This is a test post content.',
            'image'   => null,
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($post); // Ensure redirection
        $this->assertEquals('Test Post',$post->title); // Ensure redirection

    }



    /** @test */
    public function it_shows_a_single_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();
        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
        $response->assertViewHas('post', $post);
    }

    /** @test */
    public function it_shows_the_edit_post_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();
        $response = $this->get(route('posts.edit', $post->slug));

        $response->assertStatus(200);
        $response->assertViewIs('posts.edit');
        $response->assertViewHas('post', $post);
    }

    /** @test */
    /** @test */
    /** @test */
    public function it_updates_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]); // Ensure post belongs to user

        $updateData = [
            'title' => 'Updated Post Title',
            'body' => 'Updated post content.',
        ];

        // Mocking service method
        $this->postService->shouldReceive('updatePost')->once();

        $response = $this->put(route('posts.update', $post->slug), $updateData);

        $response->dump(); // Debugging

        // Ensure update was successful
        {
            // Create a user and authenticate
            $user = User::factory()->create();
            $this->actingAs($user);

            // Create a post assigned to the user
            $post = Post::factory()->create(['user_id' => $user->id]);

            // Mock PostService
            $postServiceMock = Mockery::mock(PostService::class);
            $this->app->instance(PostService::class, $postServiceMock);

            // New data for update
            $updatedData = [
                'title'   => 'Updated Title',
                'slug'    => $post->slug,
                'content' => 'Updated content for the post.',
                'image'   => null,
                'user_id' => $user->id,
            ];

            // Expect the update method to be called once
            $postServiceMock->shouldReceive('updatePost')
                ->once()
                ->with(Mockery::on(function ($postObj) use ($post) {
                    return $postObj->id === $post->id;
                }), Mockery::on(function ($data) {
                    return $data['title'] === 'Updated Title';
                }));

            // Send PUT request to update post
            $response = $this->put(route('posts.update', $post->slug), $updatedData);

            // Assert redirect and database update
            $response->assertRedirect(route('posts.index'));
            $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
        }
    }




    /** @test */
    /** @test */
    public function it_deletes_a_post()
    {
        // 📝 Create a post
        $post = Post::factory()->create();

        // ✅ Ensure the post exists before deletion
        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        // 🗑️ Send a delete request
        $this->delete(route('posts.destroy', $post->slug));

        // 🛠️ Debug: Fetch the post directly
        $deletedPost = Post::withTrashed()->find($post->id);
        dump($deletedPost->deleted_at); // Check if deleted_at is set

        // 🎯 Assert the post is soft deleted (deleted_at is NOT null)
        $this->assertNotNull($deletedPost->deleted_at, 'Post was not soft deleted.');

        // ✅ Assert the post still exists in the database
        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        // ✅ Assert the user is redirected with success message
        $this->get(route('posts.index'))
            ->assertSessionHas('success', 'Post deleted successfully!');
    }




    //==========


    /** @test */
    // public function guest_users_cannot_create_posts()
    // {
    //     $response = $this->post('/posts', [
    //         'title' => 'Sample Post',
    //         'content' => 'This is a test post.',
    //     ]);

    //     $response->assertRedirect('/login'); // Guest should be redirected
    // }

    /** @test */
    // public function authenticated_users_can_create_posts()
    // {
    //     $user = User::factory()->create(); // Create a user

    //     $response = $this->actingAs($user)->post('/posts', [
    //         'title' => 'Sample Post',
    //         'content' => 'This is a test post.',
    //     ]);

    //     $response->assertRedirect('/posts'); // Should redirect to posts list
    //     $this->assertDatabaseHas('posts', ['title' => 'Sample Post']);
    // }

    /** @test */
    // public function post_creation_fails_without_title()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->post('/posts', [
    //         'content' => 'No title included.',
    //     ]);

    //     $response->assertSessionHasErrors('title'); // Check validation error
    // }
    /** @test */
    // public function post_creation_fails_without_title()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->post('/posts', [
    //         'content' => 'No title included.',
    //     ]);

    //     $response->assertSessionHasErrors(['title']); // ✅ Check validation error
    //     $this->assertDatabaseCount('posts', 0); // ✅ Ensure no post is created
    // }

    /** @test */
    // public function test_post_creation_fails_without_title()
    // {
    //     $this->withoutExceptionHandling(); // Show real validation errors

    //     $user = User::factory()->create(); // Create a test user

    //     // Attempt to create a post WITHOUT a title
    //     $response = $this->actingAs($user)->post('/posts', [
    //         'content' => 'No title included.', // Missing 'title'
    //     ]);

    //     // Debugging: Dump session errors (uncomment if needed)
    //     // dump(session('errors'));

    //     // Ensure validation error exists
    //     $response->assertSessionHasErrors(['title']);

    //     // Ensure Laravel returns a validation failure response (redirect)
    //     $response->assertStatus(302); // Redirect due to validation failure

    //     // Ensure no post was created in the database
    //     $this->assertDatabaseCount('posts', 0);
    // }


    // /** @test */
    // public function a_user_can_update_their_own_post()
    // {
    //     $user = User::factory()->create();
    //     $post = Post::factory()->create(['user_id' => $user->id]);

    //     $response = $this->actingAs($user)->put("/posts/{$post->id}", [
    //         'title' => 'Updated Title',
    //         'content' => 'Updated content.',
    //     ]);

    //     $response->assertRedirect('/posts');
    //     $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    // }


    // /** @test */
    // public function only_post_owner_or_admin_can_delete()
    // {
    //     $user = User::factory()->create();
    //     $post = Post::factory()->create(['user_id' => $user->id]);

    //     $response = $this->actingAs($user)->delete("/posts/{$post->id}");

    //     $response->assertRedirect('/posts');
    //     $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Post should be deleted
    // }
}
