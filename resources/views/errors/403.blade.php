<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Truy cập bị từ chối') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h1 class="text-6xl font-bold text-red-500 mb-4">403</h1>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Bạn không có quyền truy cập trang này</h3>
                <p class="text-gray-500 mb-6">Xin lỗi, tài khoản của bạn không sở hữu quyền hạn để thực hiện thao tác hoặc xem nội dung này.</p>
                <a href="{{ route('home') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-600 transition">
                    Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</x-app-layout>