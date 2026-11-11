<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 1. Danh sách bài viết của cá nhân thành viên đang đăng nhập
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('author.posts.index', compact('posts'));
    }

    // 2. Form tạo bài viết mới
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.posts.create', compact('categories', 'tags'));
    }

    // 3. Lưu bài viết mới vào DB
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'category_id', 'status', 'published_at']);

        // Tạo slug độc lập, kiểm tra cả các bài trong thùng rác tránh lỗi trùng lặp
        $slug = Str::slug($request->title);
        $count = Post::where('slug', $slug)->withTrashed()->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }
        $data['slug'] = $slug;
        $data['user_id'] = auth()->id();

        // TỰ ĐỘNG GÁN THỜI GIAN NẾU CHỌN PUBLISHED MÀ CHƯA ĐIỀN NGÀY GIỜ
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Xử lý upload ảnh thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        $post = Post::create($data);

        // Gắn tags nếu có
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        // ĐIỀU HƯỚNG VỀ TRANG DASHBOARD
        return redirect()->route('dashboard')->with('success', 'Tạo bài viết thành công!');
    }

    // 4. Form sửa bài viết (CHẶN Ở SERVER: chỉ tác giả mới được sửa)
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('author.posts.edit', compact('post', 'categories', 'tags'));
    }

    // 5. Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền cập nhật bài viết này.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'category_id', 'status', 'published_at']);

        if ($data['title'] !== $post->title) {
            $slug = Str::slug($request->title);
            $count = Post::where('slug', $slug)->where('id', '!=', $post->id)->withTrashed()->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }
            $data['slug'] = $slug;
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        $post->update($data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        // ĐIỀU HƯỚNG VỀ TRANG DASHBOARD
        return redirect()->route('dashboard')->with('success', 'Cập nhật bài viết thành công!');
    }

    // 6. Xóa bài viết (Đưa vào thùng rác - Soft Deletes)
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    // 7. Xem danh sách bài trong thùng rác
    public function trash()
    {
        $posts = Post::onlyTrashed()->where('user_id', auth()->id())->paginate(10);
        return view('author.posts.trash', compact('posts'));
    }

    // 8. Khôi phục bài từ thùng rác
    public function restore($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $post->restore();

        return redirect()->route('author.posts.trash')->with('success', 'Khôi phục bài viết thành công!');
    }

    // 9. Xóa vĩnh viễn bài viết
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->forceDelete();

        return redirect()->route('author.posts.trash')->with('success', 'Đã xóa vĩnh viễn bài viết.');
    }
}
