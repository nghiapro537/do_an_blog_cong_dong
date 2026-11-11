<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trang chủ Community Blog') }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="container">
            <div class="row">
                <!-- Danh sách bài viết -->
                <div class="col-md-8">
                    <h3 class="mb-4 font-weight-bold">Bài viết mới nhất</h3>

                    @forelse($posts as $post)
                    <div class="card mb-4 shadow-sm p-4 bg-white">
                        @if($post->thumbnail)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="img-fluid rounded" style="max-height: 350px; width: 100%; object-fit: cover;">
                        </div>
                        @endif

                        @if($post->category)
                        <div>
                            <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
                        </div>
                        @endif

                        <h3 class="fw-bold">
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <small class="text-muted mb-2 d-block">Đăng bởi {{ $post->user->name ?? 'Admin' }} vào {{ $post->published_at }}</small>

                        <p class="text-muted">{{ Str::limit($post->content, 150) }}</p>

                        <div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary btn-sm">Đọc tiếp & Bình luận →</a>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">Không tìm thấy bài viết nào phù hợp.</div>
                    @endforelse

                    <!-- Phân trang -->
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>

                <!-- Sidebar tìm kiếm và danh mục -->
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 bg-white mb-4">
                        <h5 class="fw-bold mb-3">Tìm kiếm</h5>
                        <form action="{{ route('home') }}" method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Từ khóa..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                        </form>
                    </div>

                    <div class="card shadow-sm p-3 bg-white">
                        <h5 class="fw-bold mb-3">Danh mục</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach($categories as $cat)
                            <li class="mb-2">
                                <a href="{{ route('home', ['category' => $cat->slug]) }}" class="text-decoration-none">
                                    - {{ $cat->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>