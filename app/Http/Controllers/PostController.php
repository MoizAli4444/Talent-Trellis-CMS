<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\Helper;
use App\Jobs\SendPostNotification;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        return $this->postService->getAllPosts($request);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = $this->postService->createPost($request);

        SendPostNotification::dispatch(Auth::user(), $post); // Dispatch the job
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an article.');
        }
        // check if post exists
        $post = findOrAborts(Post::class, 'slug', $slug);
     
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // check if post exists
        $post = findOrAborts(Post::class, 'slug', $slug);
        // check if user has permission to edit
        authorizeOwnership($post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $slug)
    {
        // check if post exists
        $post = findOrAborts(Post::class, 'slug', $slug);
        // check if user has permission to update
        authorizeOwnership($post);
        $this->postService->updatePost($post, $request);
        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy($slug)
    {
        // check if post exists
        $post = findOrAborts(Post::class, 'slug', $slug);
        // check if user has permission to delete
        authorizeOwnership($post);
        $this->postService->deletePost($post);
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
