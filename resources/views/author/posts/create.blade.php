<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tạo bài viết mới
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm p-4 bg-white rounded">

                <form action="{{ route('author.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Tiêu đề bài viết</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Danh mục</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Ảnh đại diện (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                        @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Nội dung bài viết</label>
                        <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="draft">Bản nháp (Draft)</option>
                            <option value="published">Xuất bản ngay (Published)</option>
                            <option value="scheduled">Hẹn giờ (Scheduled)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Thời gian xuất bản (nếu hẹn giờ)</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                    </div>

                    <button type="submit" class="btn btn-success">Lưu bài viết</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Quay lại</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>