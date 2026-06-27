<?php

namespace App\Http\Controllers;

use App\Models\CastMember;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CastMemberController extends Controller
{
    public function index()
    {
        $castMembers = CastMember::with('film')->latest()->paginate(10);
        $films = Film::all();
        $castMemberData = $castMembers->keyBy('id')->map(fn($c) => [
            'nama' => $c->name,
            'karakter' => $c->character_name ?? '-',
            'film' => $c->film?->title ?? '-',
            'film_id' => $c->film_id,
            'peran' => $c->role_type ?? '-',
            'usia' => $c->age ?? '-',
            'phone' => $c->phone ?? '-',
            'catatan' => $c->notes ?? '-',
            'status' => $c->status,
        ]);
        return view('pages.cast-members.index', compact('castMembers', 'films', 'castMemberData'));
    }

    public function create()
    {
        return redirect()->route('cast-members.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'character_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'role_type' => 'nullable|string|max:50',
            'age' => 'nullable|integer|min:0|max:200',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $castMember = CastMember::create($validated);

        ActivityLog::create([
            'description' => 'Pemeran "' . $castMember->name . '" ditambahkan ke daftar cast',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'cast-member',
        ]);

        return redirect()->route('cast-members.index')->with('success', 'Pemeran berhasil ditambahkan!');
    }

    public function show(CastMember $castMember)
    {
        $castMember->load('film');
        return response()->json($castMember);
    }

    public function edit(CastMember $castMember)
    {
        $castMember->load('film');
        return response()->json($castMember);
    }

    public function update(Request $request, CastMember $castMember)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'character_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'role_type' => 'nullable|string|max:50',
            'age' => 'nullable|integer|min:0|max:200',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $castMember->update($validated);

        ActivityLog::create([
            'description' => 'Pemeran "' . $castMember->name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'cast-member',
        ]);

        return redirect()->route('cast-members.index')->with('success', 'Pemeran berhasil diperbarui!');
    }

    public function destroy(CastMember $castMember)
    {
        $name = $castMember->name;
        $castMember->delete();

        ActivityLog::create([
            'description' => 'Pemeran "' . $name . '" dihapus dari daftar cast',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'cast-member',
        ]);

        return redirect()->route('cast-members.index')->with('success', 'Pemeran berhasil dihapus!');
    }
}
