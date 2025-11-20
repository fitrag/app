<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index(Novel $novel)
    {
        $chapters = $novel->chapters()->orderBy('chapter_number')->paginate(15);
        return view('admin.chapters.index', compact('novel', 'chapters'));
    }

    public function create(Novel $novel)
    {
        // Get next chapter number
        $nextChapterNumber = $novel->chapters()->max('chapter_number') + 1;
        return view('admin.chapters.create', compact('novel', 'nextChapterNumber'));
    }

    public function store(Request $request, Novel $novel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|min:1|unique:chapters,chapter_number,NULL,id,novel_id,' . $novel->id,
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        $chapter = $novel->chapters()->create([
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'content' => $request->content,
            'status' => $request->status
        ]);

        return redirect()->route('admin.novels.chapters.index', $novel)->with('success', 'Chapter berhasil ditambahkan.');
    }

    public function show(Novel $novel, Chapter $chapter)
    {
        // Ensure chapter belongs to novel
        if ($chapter->novel_id !== $novel->id) {
            abort(404);
        }
        
        return view('admin.chapters.show', compact('novel', 'chapter'));
    }

    public function edit(Novel $novel, Chapter $chapter)
    {
        // Ensure chapter belongs to novel
        if ($chapter->novel_id !== $novel->id) {
            abort(404);
        }
        
        return view('admin.chapters.edit', compact('novel', 'chapter'));
    }

    public function update(Request $request, Novel $novel, Chapter $chapter)
    {
        // Ensure chapter belongs to novel
        if ($chapter->novel_id !== $novel->id) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|min:1|unique:chapters,chapter_number,' . $chapter->id . ',id,novel_id,' . $novel->id,
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        $chapter->update([
            'title' => $request->title,
            'chapter_number' => $request->chapter_number,
            'content' => $request->content,
            'status' => $request->status
        ]);

        return redirect()->route('admin.novels.chapters.index', $novel)->with('success', 'Chapter berhasil diperbarui.');
    }

    public function destroy(Novel $novel, Chapter $chapter)
    {
        // Ensure chapter belongs to novel
        if ($chapter->novel_id !== $novel->id) {
            abort(404);
        }

        $chapter->delete();

        // Reorder remaining chapters
        $this->reorderChapters($novel);

        return redirect()->route('admin.novels.chapters.index', $novel)->with('success', 'Chapter berhasil dihapus.');
    }

    public function reorder(Request $request, Novel $novel)
    {
        $request->validate([
            'chapters' => 'required|array',
            'chapters.*.id' => 'required|exists:chapters,id',
            'chapters.*.chapter_number' => 'required|integer|min:1'
        ]);

        foreach ($request->chapters as $chapterData) {
            Chapter::where('id', $chapterData['id'])
                   ->where('novel_id', $novel->id)
                   ->update(['chapter_number' => $chapterData['chapter_number']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan chapter berhasil diperbarui.']);
    }

    private function reorderChapters(Novel $novel)
    {
        $chapters = $novel->chapters()->orderBy('chapter_number')->get();
        foreach ($chapters as $index => $chapter) {
            $chapter->update(['chapter_number' => $index + 1]);
        }
    }
}