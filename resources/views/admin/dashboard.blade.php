<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trang Quản trị Hệ thống (Admin Dashboard)
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            <!-- Menu điều hướng trong Admin -->
            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">📊 Thống kê tổng quan</a>
                <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary">📝 Quản lý bài viết</a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">📁 Quản lý danh mục</a>
                <a href="{{ route('home') }}" class="btn btn-outline-success float-end">🌍 Về trang chủ</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Thẻ thống kê (Cards) -->
            <div class="row text-white mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary p-3 shadow-sm">
                        <h5>👥 Tổng thành viên</h5>
                        <h3>{{ $userCount }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success p-3 shadow-sm">
                        <h5>📝 Bài viết đã đăng</h5>
                        <h3>{{ $postCount }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info p-3 shadow-sm">
                        <h5>💬 Tổng bình luận</h5>
                        <h3>{{ $commentCount }}</h3>
                    </div>
                </div>
            </div>

            <!-- Top 5 bài viết nhiều bình luận nhất -->
            <div class="card shadow-sm p-4 bg-white">
                <h4 class="mb-3">🔥 Top 5 bài viết có nhiều bình luận nhất</h4>
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tiêu đề bài viết</th>
                            <th>Tác giả</th>
                            <th>Số bình luận</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPosts as $index => $post)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->user->name ?? 'Ẩn danh' }}</td>
                            <td><span class="badge bg-danger">{{ $post->comments_count }} bình luận</span></td>
                            <td>
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-info text-white">Xem</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có bài viết nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>