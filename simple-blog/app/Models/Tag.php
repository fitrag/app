<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'user_id'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users that are following this tag.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'tag_user')->withTimestamps();
    }
}
