<?php

namespace App\Http\Controllers;

use App\Models\Script;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    public function index()
    {
        $scripts = Script::with('film')->latest()->paginate(10);
        $films = Film::all();
        $scriptsData = $scripts->keyBy('id')->map(fn($s) => [
            'judul' => $s->title,
            'film' => $s->film?->title ?? '-',
            'film_id' => $s->film_id,
            'penulis' => $s->writer ?? '-',
            'versi' => $s->version ?? '-',
            'halaman' => $s->page_count ?? '-',
            'status' => $s->status,
            'catatan' => $s->revision_notes ?? '-',
        ]);
        return view('pages.scripts.index', compact('scripts', 'films', 'scriptsData'));
    }

    public function create()
    {
        return redirect()->route('scripts.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'title' => 'required|string|max:255',
            'writer' => 'nullable|string|max:255',
            'version' => 'nullable|string|max:50',
            'page_count' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50',
            'revision_notes' => 'nullable|string',
        ]);

        $script = Script::create($validated);

        ActivityLog::create([
            'description' => 'Skenario "' . $script->title . '" ditambahkan ke daftar skenario',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'script',
        ]);

        return redirect()->route('scripts.index')->with('success', 'Skenario berhasil ditambahkan!');
    }

    public function show(Script $script)
    {
        $script->load('film');
        return response()->json($script);
    }

    public function edit(Script $script)
    {
        $script->load('film');
        return response()->json($script);
    }

    public function update(Request $request, Script $script)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'title' => 'required|string|max:255',
            'writer' => 'nullable|string|max:255',
            'version' => 'nullable|string|max:50',
            'page_count' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50',
            'revision_notes' => 'nullable|string',
        ]);

        $script->update($validated);

        ActivityLog::create([
            'description' => 'Skenario "' . $script->title . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'script',
        ]);

        return redirect()->route('scripts.index')->with('success', 'Skenario berhasil diperbarui!');
    }

    public function destroy(Script $script)
    {
        $title = $script->title;
        $script->delete();

        ActivityLog::create([
            'description' => 'Skenario "' . $title . '" dihapus dari daftar skenario',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'script',
        ]);

        return redirect()->route('scripts.index')->with('success', 'Skenario berhasil dihapus!');
    }
}
