<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendArticlePublishedEmail;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->articleService->getAllArticles($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest  $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an article.');
        }

        $article =  $this->articleService->createArticle($request);
        SendArticlePublishedEmail::dispatch($article); // Dispatch the job
        return redirect()->route('articles.index')->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        // check if post exists
        $article = findOrAborts(Article::class, 'slug', $slug);

        return view('articles.show', compact('article'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // check if post exists
        $article = findOrAborts(Article::class, 'slug', $slug);
        // check if user has permission to edit
        authorizeOwnership($article);
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, $slug)
    {
        // check if post exists
        $article = findOrAborts(Article::class, 'slug', $slug);
        // check if user has permission to update
        authorizeOwnership($article);
        $this->articleService->updateArticle($article, $request);
        return redirect()->route('articles.index')->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        // check if post exists
        $article = findOrAborts(Article::class, 'slug', $slug);
        // check if user has permission to delete
        authorizeOwnership($article);
        $this->articleService->deleteArticle($article);
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }
}
