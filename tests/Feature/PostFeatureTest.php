<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFeatureTest extends TestCase
{
    use RefreshDatabase;

    // Test 1: Khách có thể xem trang chủ thành công (Thành công)
    public function test_guest_can_view_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    // Test 2: Thành viên đăng nhập có thể tạo bài viết thành công (Thành công)
    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('author.posts.store'), [
            'title' => 'Bài viết kiểm thử',
            'content' => 'Nội dung kiểm thử bài viết...',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response->assertRedirect(route('author.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Bài viết kiểm thử']);
    }

    // Test 3: Tạo bài viết thất bại khi thiếu tiêu đề (Thất bại)
    public function test_create_post_fails_when_title_is_missing(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('author.posts.store'), [
            'title' => '', // Thiếu title
            'content' => 'Nội dung...',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        $response->assertSessionHasErrors('title');
    }

    // Test 4: Thành viên không thể sửa bài viết của người khác (Thất bại - Chặn ở Server)
    public function test_user_cannot_update_others_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
        ]);

        // User khác cố gắng gửi request sửa bài của $owner
        $response = $this->actingAs($otherUser)->put(route('author.posts.update', $post->id), [
            'title' => 'Tiêu đề đã sửa',
            'content' => 'Nội dung đã sửa',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        // Phải trả về lỗi 403 (Unauthorized)
        $response->assertStatus(403);
    }

    // Test 5: Tác giả có thể xóa bài viết đưa vào thùng rác (Thành công)
    public function test_author_can_soft_delete_their_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->delete(route('author.posts.destroy', $post->id));

        $response->assertRedirect(route('author.posts.index'));
        $this->assertSoftDeleted($post);
    }
}
