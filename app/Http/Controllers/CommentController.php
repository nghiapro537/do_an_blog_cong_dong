<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Mail\NewCommentNotification; // 1. Thêm dòng này để gọi Mailable
use Illuminate\Support\Facades\Mail; // 2. Thêm dòng này để dùng facade Mail
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // 1. Lưu bình luận mới
    public function store(Request $request, $postSlug)
    {
        $request->validate([
            'content' => 'required|max:1000',
        ]);

        // Tìm bài viết dựa vào slug (hoặc ID) từ URL
        $post = Post::where('slug', $postSlug)->orWhere('id', $postSlug)->firstOrFail();

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // 3. Gửi email thông báo cho tác giả bài viết
        if ($post->user) {
            Mail::to($post->user->email)->queue(new NewCommentNotification($comment));
        }

        return back()->with('success', 'Bình luận của bạn đã được đăng!');
    }

    // 2. Xóa bình luận (Logic phân quyền: Admin HOẶC Chủ nhân bình luận HOẶC Tác giả bài viết)
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $comment->user_id === $user->id || $comment->post->user_id === $user->id) {
            $comment->delete();
            return back()->with('success', 'Đã xóa bình luận thành công.');
        }

        abort(403, 'Bạn không có quyền xóa bình luận này.');
    }
}
