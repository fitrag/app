<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'actor_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function getMessageAttribute()
    {
        $actorName = $this->actor->name;
        
        switch ($this->type) {
            case 'post_love':
                $postTitle = $this->notifiable->title ?? 'your post';
                return "{$actorName} loved your post \"{$postTitle}\"";
            
            case 'comment_reply':
                return "{$actorName} replied to your comment";
            
            case 'comment_love':
                return "{$actorName} loved your comment";
            
            case 'post_comment':
                $postTitle = $this->notifiable->title ?? 'your post';
                return "{$actorName} commented on your post \"{$postTitle}\"";
            
            default:
                return "{$actorName} interacted with your content";
        }
    }

    public function getLinkAttribute()
    {
        if (in_array($this->type, ['post_love', 'post_comment']) && $this->notifiable_type === 'App\\Models\\Post') {
            return route('blog.show', $this->notifiable->slug);
        }
        
        if (in_array($this->type, ['comment_reply', 'comment_love']) && $this->notifiable_type === 'App\\Models\\Comment') {
            return route('blog.show', $this->notifiable->post->slug) . '#comment-' . $this->notifiable->id;
        }
        
        return '#';
    }
}
