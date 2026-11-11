<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment_on_published_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->post(route('comments.store', $post->slug), [
            'content' => 'Bài viết rất hay!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', ['content' => 'Bài viết rất hay!']);
    }

    public function test_comment_fails_if_content_is_empty(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->post(route('comments.store', $post->slug), [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_post_author_can_delete_comment_on_their_post(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $author->id,
            'category_id' => $category->id,
        ]);

        $comment = Comment::create([
            'content' => 'Bình luận rác',
            'user_id' => $commenter->id,
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($author)->delete(route('comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
