<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'published_at',
        'user_id',
        'category_id',
    ];

    // Tác giả của bài viết
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Danh mục của bài viết
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Các tag của bài viết
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Các bình luận dưới bài viết
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
