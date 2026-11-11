<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lỗi máy chủ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h1 class="text-6xl font-bold text-gray-700 mb-4">500</h1>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Đã xảy ra lỗi ở hệ thống</h3>
                <p class="text-gray-500 mb-6">Chúng tôi đang gặp chút sự cố kỹ thuật bên trong máy chủ. Vui lòng quay lại sau ít phút.</p>
                <a href="{{ route('home') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-600 transition">
                    Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>
</x-app-layout>