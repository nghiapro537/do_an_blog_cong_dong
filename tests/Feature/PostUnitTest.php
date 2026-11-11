<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostUnitTest extends TestCase
{
    use RefreshDatabase;

    // Unit Test 1: Kiểm tra quan hệ Bài viết thuộc về một Tác giả (User)
    public function test_post_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    // Unit Test 2: Kiểm tra trạng thái mặc định hoặc slug của bài viết
    public function test_post_slug_generation_and_status(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::create([
            'title' => 'Học Laravel Cơ Bản',
            'slug' => 'hoc-laravel-co-ban',
            'content' => 'Nội dung học laravel...',
            'status' => 'draft',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertEquals('hoc-laravel-co-ban', $post->slug);
        $this->assertEquals('draft', $post->status);
    }
}
