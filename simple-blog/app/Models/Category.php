<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'user_id'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users that are interested in this category.
     */
    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'category_user')->withTimestamps();
    }

    /**
     * Alias for interestedUsers (followers).
     */
    public function followers()
    {
        return $this->interestedUsers();
    }

    /**
     * Scope to get hot categories based on this week's activity
     */
    public function scopeHotThisWeek($query, $limit = 5)
    {
        $oneWeekAgo = now()->subWeek();

        return $query->withCount([
            'posts as posts_this_week' => function ($query) use ($oneWeekAgo) {
                $query->where('is_published', true)
                      ->where('created_at', '>=', $oneWeekAgo);
            },
            'posts as total_views' => function ($query) use ($oneWeekAgo) {
                $query->where('is_published', true)
                      ->where('created_at', '>=', $oneWeekAgo);
            }
        ])
        ->withSum([
            'posts as views_sum' => function ($query) use ($oneWeekAgo) {
                $query->where('is_published', true)
                      ->where('created_at', '>=', $oneWeekAgo);
            }
        ], 'views')
        ->having('posts_this_week', '>', 0)
        ->orderByDesc('posts_this_week')
        ->orderByDesc('views_sum')
        ->limit($limit);
    }
}
