<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::latest()->paginate(10);
        $filmData = $films->keyBy('id')->map(fn($f) => [
            'judul' => $f->title,
            'genre' => $f->genre,
            'sutradara' => $f->director,
            'tahun' => $f->year,
            'anggaran' => $f->budget ? 'Rp ' . number_format($f->budget, 0, ',', '.') : '-',
            'status' => $f->status,
            'sinopsis' => $f->synopsis ?? '-',
            'image' => $f->image ? Storage::url($f->image) : null,
            'is_focus' => $f->is_focus,
        ])->toArray();
        return view('pages.films.index', compact('films', 'filmData'));
    }

    public function create()
    {
        return redirect()->route('films.index');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'crew') {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2099',
            'director' => 'nullable|string|max:255',
            'producer' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_focus' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('films', 'public');
        }

        $validated['is_focus'] = $request->boolean('is_focus');
        if ($validated['is_focus']) {
            Film::where('is_focus', true)->update(['is_focus' => false]);
        }

        $film = Film::create($validated);

        ActivityLog::create([
            'description' => 'Film "' . $film->title . '" ditambahkan ke daftar proyek',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'film',
        ]);

        return redirect()->route('films.index')->with('success', 'Film berhasil ditambahkan!');
    }

    public function show(Film $film)
    {
        $film->image_url = $film->image ? Storage::url($film->image) : null;
        return response()->json($film);
    }

    public function edit(Film $film)
    {
        $film->image_url = $film->image ? Storage::url($film->image) : null;
        return response()->json($film);
    }

    public function update(Request $request, Film $film)
    {
        if (auth()->user()->role === 'crew') {
            abort(403, 'Unauthorized');
        }
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2099',
            'director' => 'nullable|string|max:255',
            'producer' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_focus' => 'nullable|boolean',
        ];

        $validated = $request->validate($rules);

        if ($request->hasFile('image')) {
            if ($film->image) {
                Storage::disk('public')->delete($film->image);
            }
            $validated['image'] = $request->file('image')->store('films', 'public');
        }

        if ($request->has('is_focus')) {
            $validated['is_focus'] = $request->boolean('is_focus');
            if ($validated['is_focus']) {
                Film::where('is_focus', true)->where('id', '!=', $film->id)->update(['is_focus' => false]);
            }
        }

        $film->update($validated);

        ActivityLog::create([
            'description' => 'Film "' . $film->title . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'film',
        ]);

        return redirect()->route('films.index')->with('success', 'Film berhasil diperbarui!');
    }

    public function destroy(Film $film)
    {
        if (auth()->user()->role === 'crew') {
            abort(403, 'Unauthorized');
        }
        $title = $film->title;
        if ($film->image) {
            Storage::disk('public')->delete($film->image);
        }
        $film->delete();

        ActivityLog::create([
            'description' => 'Film "' . $title . '" dihapus dari daftar proyek',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'film',
        ]);

        return redirect()->route('films.index')->with('success', 'Film berhasil dihapus!');
    }
}
