<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\User;
use App\Models\NovelGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $genre = $request->get('genre');
        $author = $request->get('author');
        
        $novels = Novel::with('user', 'genres')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($genre, function ($query, $genre) {
                    return $query->whereHas('genres', function ($q) use ($genre) {
                        $q->where('genre', $genre);
                    });
                })
                ->when($author, function ($query, $author) {
                    return $query->where('user_id', $author);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        $authors = User::where('role', 'kreator')->orWhere('role', 'admin')->get();
        $genres = Novel::GENRES;

        return view('admin.novels.index', compact('novels', 'authors', 'genres'));
    }

    public function create()
    {
        $authors = User::where('role', 'kreator')->orWhere('role', 'admin')->get();
        $genres = Novel::GENRES;
        return view('admin.novels.create', compact('authors', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array|min:1',
            'genres.*' => 'in:' . implode(',', array_keys(Novel::GENRES)),
            'status' => 'required|in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
            'is_featured' => 'boolean'
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('novel-covers', 'public');
        }

        $novel = Novel::create([
            'title' => $request->title,
            'description' => $request->description,
            'cover_image' => $coverImagePath,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'is_featured' => $request->has('is_featured')
        ]);

        // Sync genres
        $novel->syncGenres($request->genres);

        return redirect()->route('admin.novels.index')->with('success', 'Novel berhasil ditambahkan.');
    }

    public function show(Novel $novel)
    {
        $novel->load('user', 'chapters', 'genres');
        return view('admin.novels.show', compact('novel'));
    }

    public function edit(Novel $novel)
    {
        $novel->load('genres');
        $authors = User::where('role', 'kreator')->orWhere('role', 'admin')->get();
        $genres = Novel::GENRES;
        return view('admin.novels.edit', compact('novel', 'authors', 'genres'));
    }

    public function update(Request $request, Novel $novel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array|min:1',
            'genres.*' => 'in:' . implode(',', array_keys(Novel::GENRES)),
            'status' => 'required|in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
            'is_featured' => 'boolean'
        ]);

        $coverImagePath = $novel->cover_image;
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($novel->cover_image) {
                Storage::disk('public')->delete($novel->cover_image);
            }
            $coverImagePath = $request->file('cover_image')->store('novel-covers', 'public');
        }

        $novel->update([
            'title' => $request->title,
            'description' => $request->description,
            'cover_image' => $coverImagePath,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'is_featured' => $request->has('is_featured')
        ]);

        // Sync genres
        $novel->syncGenres($request->genres);

        return redirect()->route('admin.novels.index')->with('success', 'Novel berhasil diperbarui.');
    }

    public function destroy(Novel $novel)
    {
        // Delete cover image if exists
        if ($novel->cover_image) {
            Storage::disk('public')->delete($novel->cover_image);
        }
        
        $novel->delete();
        return redirect()->route('admin.novels.index')->with('success', 'Novel berhasil dihapus.');
    }

    public function toggleFeatured(Novel $novel)
    {
        $novel->update([
            'is_featured' => !$novel->is_featured,
        ]);

        $status = $novel->is_featured ? 'di-featured' : 'dikeluarkan dari featured';
        return redirect()->route('admin.novels.index')->with('success', 'Novel berhasil ' . $status . '.');
    }
}