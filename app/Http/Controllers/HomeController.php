<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các bài viết có trạng thái 'published' và đã đến thời gian xuất bản
        $query = Post::with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->where('published_at', '<=', now());

        // Tìm kiếm theo từ khóa tiêu đề hoặc nội dung
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục (category slug)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Lọc theo tag (tag slug)
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->input('tag'));
            });
        }

        // Phân trang 6 bài viết mỗi trang, giữ lại các tham số query trên URL khi phân trang
        $posts = $query->latest('published_at')->paginate(6)->withQueryString();

        // Lấy danh sách danh mục và tag để hiển thị ở sidebar lọc
        $categories = Category::all();
        $tags = Tag::all();

        return view('home', compact('posts', 'categories', 'tags'));
    }

    // Hiển thị chi tiết bài viết theo slug
    public function show($slug)
    {
        $post = Post::with(['user', 'category', 'tags', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }
}
