<!DOCTYPE html>
<html>

<head>
    <title>Bình luận mới</title>
</head>

<body>
    <h2>Xin chào {{ $comment->post->user->name }},</h2>
    <p>Bài viết <strong>{{ $comment->post->title }}</strong> của bạn vừa có bình luận mới từ <strong>{{ $comment->user->name }}</strong>:</p>
    <blockquote>"{{ $comment->content }}"</blockquote>
    <p>Truy cập website để xem chi tiết.</p>
</body>

</html>