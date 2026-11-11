<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản Admin mẫu
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // 2. Tạo tài khoản Member mẫu
        $member = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'member',
        ]);

        // 3. Tạo Categories và Tags
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(10)->create();

        // 4. Tạo các bài viết mẫu gắn với Categories và Tags
        Post::factory(15)->create([
            'user_id' => $member->id,
            'category_id' => fn() => $categories->random()->id,
        ])->each(function ($post) use ($tags) {
            // Gắn ngẫu nhiên từ 1 đến 3 tag cho mỗi bài viết
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });
    }
}
