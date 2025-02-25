<!DOCTYPE html>
<html>
<head>
    <title>New Article Published</title>
</head>
<body>
    <h1>Hello, {{ $article->user->name }}!</h1>
    <p>Your article titled <strong>{{ $article->title }}</strong> has been successfully published.</p>
    <p>Click below to view it:</p>
    <p><a href="{{ url('/articles/' . $article->slug) }}">View Article</a></p>
    <p>Thank you for contributing to our blog!</p>
</body>
</html>
