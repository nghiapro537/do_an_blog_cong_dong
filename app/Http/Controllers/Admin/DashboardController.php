<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Thống kê tổng quan
    public function index()
    {
        $userCount = User::count();
        $postCount = Post::where('status', 'published')->count();
        $commentCount = Comment::count();

        // 5 bài viết nhiều bình luận nhất
        $topPosts = Post::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('userCount', 'postCount', 'commentCount', 'topPosts'));
    }

    // Quản lý tất cả bài viết
    public function posts()
    {
        $posts = Post::with('user', 'category')->latest()->paginate(10);
        return view('admin.posts', compact('posts'));
    }

    // Xóa bài viết vi phạm
    public function destroyPost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết vi phạm.');
    }

    // Quản lý và xóa bình luận vi phạm
    public function comments()
    {
        $comments = Comment::with('user', 'post')->latest()->paginate(10);
        return view('admin.comments', compact('comments'));
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận vi phạm.');
    }
}
