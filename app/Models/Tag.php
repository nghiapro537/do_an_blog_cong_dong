<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Một tag thuộc nhiều bài viết (Nhiều - Nhiều)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
