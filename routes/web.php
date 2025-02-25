<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Models\Article;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Routes (No Authentication Required)
Route::get('/', function () {
    return view('public-views.landing-page');
    // return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('landing-page');
Route::get('/latest-posts', function () {
    $posts = Post::latest()->take(6)->get();
    return view('public-views.latest-posts', compact('posts'));
})->name('latest-posts');

Route::get('/latest-articles', function () {
    $articles = Article::latest()->take(6)->get();
    return view('public-views.latest-articles', compact('articles'));
})->name('latest-articles');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');



// Protected Routes (Require Authentication for Add, Update, Delete, and Create)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::resource('articles', ArticleController::class)->except(['index', 'show']);
});

// Public Routes (No Authentication Required)
Route::resource('posts', PostController::class)->only(['index', 'show']);
Route::resource('articles', ArticleController::class)->only(['index', 'show']);
