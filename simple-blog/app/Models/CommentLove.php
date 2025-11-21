<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLove extends Model
{
    protected $table = 'comment_loves';
    protected $fillable = ['comment_id', 'user_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
