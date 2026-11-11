<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quản lý tất cả bài viết (Admin)
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">📊 Thống kê tổng quan</a>
                <a href="{{ route('admin.posts') }}" class="btn btn-primary">📝 Quản lý bài viết</a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">📁 Quản lý danh mục</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm p-4 bg-white">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->user->name ?? 'N/A' }}</td>
                            <td>{{ $post->category->name ?? 'Không có' }}</td>
                            <td>
                                <span class="badge bg-{{ $post->status == 'published' ? 'success' : 'warning' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-info text-white">Xem</a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cảnh báo: Bạn có chắc muốn xóa bài viết vi phạm này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa vi phạm</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="mt-3">
                    {{ $posts->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
