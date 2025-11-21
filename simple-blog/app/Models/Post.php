<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'is_published',
        'is_commentable',
        'enable_font_adjuster',
        'published_at',
        'views',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_commentable' => 'boolean',
        'enable_font_adjuster' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The users that have bookmarked the post.
     */
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Check if the post is bookmarked by a specific user.
     */
    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarkedBy->contains($user);
    }

    /**
     * The users that have loved the post.
     */
    public function loves()
    {
        return $this->belongsToMany(User::class, 'loves')->withTimestamps();
    }

    /**
     * Check if the post is loved by a specific user.
     */
    public function isLovedBy(User $user)
    {
        return $this->loves->contains($user);
    }

    public function analytics()
    {
        return $this->hasMany(PostAnalytic::class);
    }
}
