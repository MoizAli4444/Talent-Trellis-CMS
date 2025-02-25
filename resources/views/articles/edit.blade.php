@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Article</h2>
    @can('edit post')
    <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    
        <div class="form-group">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="5" required>{{ old('content', $article->content) }}</textarea>
            @error('content')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    
        <div class="form-group">
            <label>Current Image</label>
            @if($article->image)
                <img src="{{ $article->image }}" width="150" class="img-fluid">
            @endif
        </div>
    
        <div class="form-group">
            <label>New Image</label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
    
    @endcan
</div>
@endsection
