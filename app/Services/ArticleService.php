<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ArticleService
{
    public function getAllArticles(Request $request)
    {
        if ($request->ajax()) {
            // dd("inside service ajax request");
            $articles = Article::latest()->select(['id', 'title', 'slug', 'content', 'image', 'user_id', 'created_at']);

            return DataTables::of($articles)
                ->addColumn('image', function ($article) {
                    return $article->image ? '<img src="'.$article->image.'" width="50">' : 'No Image';
                })
                ->addColumn('title', function ($article) {
                    return Str::limit($article->title, 30, '...'); 
                })
                ->addColumn('content', function ($article) {
                    return Str::limit($article->content, 50, '...'); 
                })
                ->addColumn('created_at', function ($article) {
                    return $article->created_at->toFormattedDateString(); 
                })
                ->addColumn('actions', function ($article) {
                    $buttons = '<a href="' . route('articles.show', $article->slug) . '" class="btn btn-info btn-sm">View</a>';

                    // Check if the logged-in user is the owner
                    if (Auth::id() == $article->user_id) {
                        $buttons .= '
                            <a href="' . route('articles.edit', $article->slug) . '" class="btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('articles.destroy', $article->slug) . '" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                    
                            <script>
                                function confirmDelete() {
                                    return confirm("Are you sure you want to delete this article?");
                                }
                            </script>
                        ';
                    }
                    

                    return $buttons;
                })
                ->rawColumns(['image', 'actions'])
                ->make(true);
        }

        return view('articles.index'); // Return view for non-AJAX requests
    }


    public function createArticle($data)
    {

        $imagePath = $data->file('image') ? $data->file('image')->store('images', 'public') : 'images/default.png';

        // dd("");

        return Article::create([
            'title' => $data->title,
            'content' => $data->content,
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);
    }

    public function updateArticle($article, $data)
    {
        if ($data->hasFile('image')) {
            $imagePath = $data->file('image')->store('articles', 'public');
            $article->update(['image' => $imagePath]);
        }

        return $article->update($data->except('image'));
    }

    public function deleteArticle($article)
    {
        $article->delete();
    }
}
