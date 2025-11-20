<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Novel extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    const GENRES = [
        'romance' => 'Romance',
        'fantasy' => 'Fantasy',
        'mystery' => 'Mystery',
        'sci-fi' => 'Sci-Fi',
        'horror' => 'Horror',
        'comedy' => 'Comedy',
        'drama' => 'Drama',
        'action' => 'Action',
        'adventure' => 'Adventure',
        'historical' => 'Historical',
        'thriller' => 'Thriller',
        'biography' => 'Biography',
        'general' => 'General'
    ];

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'status',
        'user_id',
        'view_count',
        'like_count',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function genres()
    {
        return $this->hasMany(NovelGenre::class);
    }

    public function getStatusBadge()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Draft</span>';
            case self::STATUS_PUBLISHED:
                return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Published</span>';
            case self::STATUS_ARCHIVED:
                return '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Archived</span>';
            default:
                return '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Unknown</span>';
        }
    }

    public function getGenresAttribute()
    {
        return $this->genres()->pluck('genre')->toArray();
    }

    public function getGenreNames()
    {
        $genreNames = [];
        foreach ($this->getGenresAttribute() as $genre) {
            $genreNames[] = self::GENRES[$genre] ?? ucfirst($genre);
        }
        return $genreNames;
    }

    public function getPrimaryGenreName()
    {
        $genres = $this->getGenresAttribute();
        if (!empty($genres)) {
            $primaryGenre = $genres[0];
            return self::GENRES[$primaryGenre] ?? ucfirst($primaryGenre);
        }
        return 'General';
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->whereHas('genres', function ($q) use ($genre) {
            $q->where('genre', $genre);
        });
    }

    public function getCoverImageUrl()
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        
        // Return default cover image
        return 'https://via.placeholder.com/300x400/cccccc/ffffff?text=No+Cover';
    }

    public function syncGenres(array $genres)
    {
        $this->genres()->delete();
        
        $genreRecords = [];
        foreach ($genres as $genre) {
            if (array_key_exists($genre, self::GENRES)) {
                $genreRecords[] = [
                    'novel_id' => $this->id,
                    'genre' => $genre,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        if (!empty($genreRecords)) {
            NovelGenre::insert($genreRecords);
        }
    }
}