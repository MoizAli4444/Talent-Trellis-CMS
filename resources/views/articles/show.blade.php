@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Article Details</h2>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $article?->title }}</h3>


                @if ($article->image)
                    <img src="{{ $article->image }}" class="img-fluid mb-3" alt="Article Image" style="max-width: 300px;">
                @endif

                <p><strong>ID:</strong> {{ $article->id }}</p>
                <p><strong>Content:</strong> {!! nl2br(e($article->content)) !!}</p>
                <p><strong>Created At:</strong> {{ $article->created_at->format('F j, Y, g:i a') }}</p>

                @if (Auth::id() === $article->user_id)
                    @can('edit post')
                        <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-warning">Edit</a>
                    @endcan
                    @can('delete post')
                        <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this article?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
