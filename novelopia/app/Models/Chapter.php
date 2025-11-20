<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'novel_id',
        'title',
        'content',
        'chapter_number',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }

    public function getStatusBadge()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Draft</span>';
            case self::STATUS_PUBLISHED:
                return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Published</span>';
            default:
                return '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Unknown</span>';
        }
    }
}