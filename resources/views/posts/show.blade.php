<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <!-- Nhúng Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container" style="max-width: 800px;">
            <div class="card shadow-sm p-4 bg-white">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Hiển thị ảnh Thumbnail chi tiết bài viết -->
                @if($post->thumbnail)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm" style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
                @endif

                <!-- Thông tin danh mục & tác giả -->
                <div class="mb-3 text-muted">
                    @if($post->category)
                    <span class="badge bg-secondary me-2">{{ $post->category->name }}</span>
                    @endif
                    <span>Đăng bởi <strong>{{ $post->user->name ?? 'Admin' }}</strong> vào {{ $post->published_at }}</span>
                </div>

                <!-- Tiêu đề -->
                <h1 class="fw-bold mb-4">{{ $post->title }}</h1>

                <!-- Nội dung chi tiết -->
                <div class="content mb-5" style="line-height: 1.8; font-size: 1.1rem;">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <hr>

                <!-- KHU VỰC BÌNH LUẬN -->
                <div class="mt-4">
                    <h4 class="mb-3">Bình luận ({{ $post->comments->count() }})</h4>

                    <!-- Form gửi bình luận (Chỉ hiện khi đã đăng nhập) -->
                    @auth
                    <form action="{{ route('comments.store', $post->slug) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" rows="3" class="form-control" placeholder="Viết bình luận của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Gửi bình luận</button>
                    </form>
                    @else
                    <div class="alert alert-light border mb-4">
                        Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để tham gia thảo luận và bình luận bài viết.
                    </div>
                    @endauth

                    <!-- Danh sách bình luận -->
                    <div class="list-group">
                        @forelse($post->comments()->latest()->get() as $comment)
                        <div class="list-group-item mb-2 rounded shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold text-dark mb-0">{{ $comment->user->name ?? 'Thành viên' }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2 text-secondary">{{ $comment->content }}</p>

                            <!-- Nút Xóa bình luận: Hiển thị nếu là Admin, hoặc chính chủ bình luận, hoặc tác giả bài viết -->
                            @auth
                            @if(auth()->user()->role === 'admin' || auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="text-end" onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 12px;">Xóa</button>
                            </form>
                            @endif
                            @endauth
                        </div>
                        @empty
                        <p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận bài viết này!</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">← Quay lại trang chủ</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>