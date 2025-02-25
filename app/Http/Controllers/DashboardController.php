<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $authUserPosts = Post::where('user_id', Auth::id())->count();

        $totalArticles = Article::count();
        $authUserArticles = Article::where('user_id', Auth::id())->count();

        return view('dashboard', compact('totalPosts', 'authUserPosts', 'totalArticles', 'authUserArticles'));
    }

}
