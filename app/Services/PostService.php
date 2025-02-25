<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class PostService
{
    public function getAllPosts(Request $request)
    {

        // 2
        if ($request->ajax()) {
            $posts = Post::latest()->select(['id','user_id', 'title', 'content', 'image', 'slug', 'created_at']);
    
            return DataTables::of($posts)
                ->addColumn('image', function ($post) {
                    // return $post->image ? '<img src="'.asset('storage/' . $post->image).'" width="50">' : 'No Image';
                    return $post->image ? '<img src="'.$post->image.'" width="50">' : 'No Image';
                })
                ->addColumn('title', function ($article) {
                    return Str::limit($article->title, 30, '...'); 
                })
                ->addColumn('content', function ($article) {
                    return Str::limit($article->content, 50, '...'); 
                })
                ->addColumn('created_at', function ($post) {
                    return $post->created_at->toFormattedDateString();
                })
                ->addColumn('actions', function ($post) {
                    $editBtn = '<a href="'.route('posts.show', $post->slug).'" class="btn btn-info btn-sm">Read</a>';
    
                    // Check if the authenticated user is the owner
                    if (Auth::id() == $post->user_id) {
                        $editBtn .= '
                            <a href="'.route('posts.edit', $post->slug).'" class="btn btn-warning btn-sm">Edit</a>
                            <form action="'.route('posts.destroy', $post->slug).'" method="POST" class="d-inline" onsubmit="return confirmDelete(this);">
                                '.csrf_field().method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                    
                            <script>
                                function confirmDelete(form) {
                                    return confirm("Are you sure you want to delete this post?");
                                }
                            </script>
                        ';
                    }
                    
    
                    return $editBtn;
                })
                ->rawColumns(['image', 'actions'])
                ->make(true);
        }
    
        return view('posts.index');
    }

    public function createPost($data)
    {
        // $imagePath = $data->file('image') ? $data->file('image')->store('images', 'public') : 'images/default.png';
        $imagePath = $data->file('image') ? $data->file('image')->store('images', 'public') : null;

        return Post::create([
            'title' => $data->title,
            'content' => $data->content,
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);
    }

    public function updatePost(Post $post, $data)
    {
        $post->update($data->except('image'));

        if ($data->hasFile('image')) {
            $imagePath = $data->file('image')->store('images', 'public');
            $post->update(['image' => $imagePath]);
        }

        return $post;
    }

    public function deletePost(Post $post)
    {
        $post->delete();
    }
}
