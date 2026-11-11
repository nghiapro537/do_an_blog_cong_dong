<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

// --- NHÓM A: TRANG CÔNG KHAI (Khách & Mọi người) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts/{slug}', [HomeController::class, 'show'])->name('posts.show');

// Dashboard chung sau khi đăng nhập (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Quản lý Profile cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- NHÓM B: TÀI KHOẢN & BÀI VIẾT (Thành viên / Tác giả) ---
Route::middleware(['auth'])->prefix('author')->name('author.')->group(function () {
    Route::get('/posts/trash', [AuthorPostController::class, 'trash'])->name('posts.trash');
    Route::post('/posts/{id}/restore', [AuthorPostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', [AuthorPostController::class, 'forceDelete'])->name('posts.force-delete');

    Route::resource('posts', AuthorPostController::class)->names('posts');
});

// --- NHÓM C: BÌNH LUẬN ---
Route::middleware(['auth'])->group(function () {
    // Dùng {post} (slug) để khớp với tham số nhận vào trong CommentController
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// --- NHÓM D: TRANG QUẢN TRỊ (Chỉ Quản trị viên Admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý bài viết toàn trang
    Route::get('/posts', [DashboardController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}', [DashboardController::class, 'destroyPost'])->name('posts.destroy');

    // Quản lý bình luận toàn trang
    Route::get('/comments', [DashboardController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}', [DashboardController::class, 'destroyComment'])->name('comments.destroy');

    // Quản lý danh mục
    Route::resource('categories', AdminCategoryController::class);
});

require __DIR__ . '/auth.php';
