<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quản lý Danh mục (Admin)
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">

            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">📊 Thống kê tổng quan</a>
                <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary">📝 Quản lý bài viết</a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">📁 Quản lý danh mục</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <!-- Form Thêm danh mục -->
                <div class="col-md-4">
                    <div class="card shadow-sm p-4 bg-white">
                        <h5 class="mb-3">Thêm danh mục mới</h5>
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên danh mục</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Thêm mới</button>
                        </form>
                    </div>
                </div>

                <!-- Bảng danh sách danh mục -->
                <div class="col-md-8">
                    <div class="card shadow-sm p-4 bg-white">
                        <h5 class="mb-3">Danh sách danh mục</h5>
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có danh mục nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>