@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Post Details</h2>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $post?->title }}</h3>


                @if ($post->image)
                    <img src="{{ $post->image }}" class="img-fluid mb-3" alt="Post Image" style="max-width: 300px;">
                @endif

                <p><strong>ID:</strong> {{ $post->id }}</p>
                <p><strong>Content:</strong> {!! nl2br(e($post->content)) !!}</p>
                <p><strong>Created At:</strong> {{ $post->created_at->format('F j, Y, g:i a') }}</p>

                @if (Auth::id() === $post->user_id)
                    @can('edit post')
                        <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-warning">Edit</a>
                    @endcan
                    @can('delete post')
                        <form action="{{ route('posts.destroy', $post->slug) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
