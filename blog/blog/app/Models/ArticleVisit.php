<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'ip_address',
        'device',
        'browser',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}

