<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CrewController extends Controller
{
    public function index()
    {
        $crews = Crew::with('film')->latest()->paginate(10);
        $films = Film::all();
        $crewData = $crews->keyBy('id')->map(fn($c) => [
            'nama' => $c->name,
            'posisi' => $c->position ?? '-',
            'departemen' => $c->department ?? '-',
            'film' => $c->film?->title ?? '-',
            'film_id' => $c->film_id,
            'phone' => $c->phone ?? '-',
            'email' => $c->email ?? '-',
            'status' => $c->status,
            'image' => $c->image,
        ]);
        return view('pages.crews.index', compact('crews', 'films', 'crewData'));
    }

    public function create()
    {
        return redirect()->route('crews.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('crews', 'public');
        }

        $crew = Crew::create($validated);

        ActivityLog::create([
            'description' => 'Crew "' . $crew->name . '" ditambahkan ke daftar kru',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'crew',
        ]);

        return redirect()->route('crews.index')->with('success', 'Crew berhasil ditambahkan!');
    }

    public function show(Crew $crew)
    {
        $crew->load('film');
        return response()->json($crew);
    }

    public function edit(Crew $crew)
    {
        $crew->load('film');
        return response()->json($crew);
    }

    public function update(Request $request, Crew $crew)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('image')) {
            if ($crew->image && Storage::disk('public')->exists($crew->image)) {
                Storage::disk('public')->delete($crew->image);
            }
            $validated['image'] = $request->file('image')->store('crews', 'public');
        }

        $crew->update($validated);

        ActivityLog::create([
            'description' => 'Crew "' . $crew->name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'crew',
        ]);

        return redirect()->route('crews.index')->with('success', 'Crew berhasil diperbarui!');
    }

    public function destroy(Crew $crew)
    {
        if ($crew->image && Storage::disk('public')->exists($crew->image)) {
            Storage::disk('public')->delete($crew->image);
        }
        $name = $crew->name;
        $crew->delete();

        ActivityLog::create([
            'description' => 'Crew "' . $name . '" dihapus dari daftar kru',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'crew',
        ]);

        return redirect()->route('crews.index')->with('success', 'Crew berhasil dihapus!');
    }
}
