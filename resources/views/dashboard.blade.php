<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bảng điều khiển (Dashboard)') }}
            </h2>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                🏠 Về trang chủ xem bài viết & bình luận
            </a>
        </div>
    </x-slot>

    <!-- Nhúng Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm p-4 mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 font-weight-bold">Xin chào, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted mb-0">Vai trò hiện tại: <span class="badge bg-secondary text-uppercase">{{ auth()->user()->role ?? 'member' }}</span></p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('author.posts.create') }}" class="btn btn-primary">➕ Thêm bài viết mới</a>
                        <a href="{{ route('author.posts.trash') }}" class="btn btn-outline-secondary">🗑️ Thùng rác</a>

                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark">⚙️ Vào khu vực Quản trị (Admin)</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm p-4 bg-white">
                <h3 class="mb-3 font-weight-bold">Danh sách bài viết của tôi</h3>

                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $myPosts = \App\Models\Post::where('user_id', auth()->id())->latest()->get();
                        @endphp

                        @forelse($myPosts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" width="60" class="rounded shadow-sm" alt="Thumb">
                                @else
                                <span class="text-muted small">Không có</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="text-decoration-none fw-bold text-primary">
                                    {{ $post->title }} 🔗
                                </a>
                            </td>
                            <td>{{ $post->category->name ?? 'Chưa phân loại' }}</td>
                            <td>
                                @if($post->status == 'published')
                                <span class="badge bg-success">Published</span>
                                @elseif($post->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                                @else
                                <span class="badge bg-warning text-dark">Scheduled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('author.posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('author.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn chuyển bài viết này vào thùng rác?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Bạn chưa có bài viết nào. Hãy bấm nút "Thêm bài viết mới" ở trên để bắt đầu!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>