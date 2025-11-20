<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovelGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'novel_id',
        'genre'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }
}