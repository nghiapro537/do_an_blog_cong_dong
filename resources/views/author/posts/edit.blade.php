<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chỉnh sửa bài viết
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container" style="max-width: 800px;">
            <div class="card shadow-sm p-4 bg-white">

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('author.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Tiêu đề bài viết</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Danh mục</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Ảnh Thumbnail hiện tại</label>
                        @if($post->thumbnail)
                        <div>
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" width="150" class="mb-2 rounded">
                        </div>
                        @else
                        <p class="text-muted">Không có ảnh</p>
                        @endif
                        <input type="file" name="thumbnail" class="form-control mt-2">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Nội dung</label>
                        <textarea name="content" rows="6" class="form-control" required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Trạng thái</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Xuất bản (Published)</option>
                            <option value="scheduled" {{ $post->status == 'scheduled' ? 'selected' : '' }}>Hẹn giờ (Scheduled)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Thời gian xuất bản (nếu hẹn giờ)</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $post->published_at ? date('Y-m-d\TH:i', strtotime($post->published_at)) : '') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
                    <!-- ĐÃ ĐỔI ĐƯỜNG DẪN VỀ DASHBOARD -->
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>