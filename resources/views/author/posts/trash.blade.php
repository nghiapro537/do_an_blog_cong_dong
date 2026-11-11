<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thùng rác bài viết của tôi
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            <div class="mb-3">
                <a href="{{ route('author.posts.index') }}" class="btn btn-secondary">&larr; Quay lại danh sách bài viết</a>
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
                            <th>Ngày xóa tạm thời</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->deleted_at }}</td>
                            <td>
                                <!-- Nút khôi phục (Phương thức POST) -->
                                <form action="{{ route('author.posts.restore', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Khôi phục</button>
                                </form>

                                <!-- Nút xóa vĩnh viễn (Phương thức DELETE) -->
                                <form action="{{ route('author.posts.force-delete', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cảnh báo: Hành động này không thể hoàn tác. Bạn có chắc muốn xóa vĩnh viễn?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa vĩnh viễn</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Thùng rác đang trống.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $posts->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>