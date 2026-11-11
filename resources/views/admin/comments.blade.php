<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quản lý Bình luận (Admin)
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">📊 Thống kê tổng quan</a>
                <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary">📝 Quản lý bài viết</a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">📁 Quản lý danh mục</a>
                <a href="{{ route('admin.comments') }}" class="btn btn-primary">💬 Quản lý bình luận</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm p-4 bg-white">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người bình luận</th>
                            <th>Nội dung bình luận</th>
                            <th>Bài viết thuộc về</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->user->name ?? 'Ẩn danh' }}</td>
                            <td>{{ $comment->body }}</td>
                            <td>
                                @if($comment->post)
                                <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank">
                                    {{ $comment->post->title }}
                                </a>
                                @else
                                Bài viết đã bị xóa
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bình luận vi phạm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa vi phạm</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có bình luận nào trong hệ thống.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $comments->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>