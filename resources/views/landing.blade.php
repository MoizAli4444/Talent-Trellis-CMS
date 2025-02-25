@extends('layouts.app')

@section('content')
<div class="container">

    {{-- 🚀 Hero Section --}}
    <div class="jumbotron text-center bg-light p-5 rounded">
        <h1 class="display-4">Welcome to Technology Blog</h1>
        <p class="lead">Stay updated with the latest trends in technology, programming, and innovation.</p>
        <a href="{{ route('articles.index') }}" class="btn btn-primary">Explore Articles</a>
    </div>

    {{-- 🔥 Featured Articles Section --}}
    <h2 class="mt-5">Latest Articles</h2>
    <div class="row">
       
    </div>

    {{-- 📢 Latest Blog Posts Section --}}
    <h2 class="mt-5">Latest Posts</h2>
    <div class="row">
       
    </div>

    {{-- ✉️ Subscribe Section --}}
    <div class="text-center mt-5 p-4 bg-dark text-white rounded">
        <h3>Subscribe for Updates</h3>
        <p>Get the latest technology news straight to your inbox.</p>
        
    </div>

</div>
@endsection
