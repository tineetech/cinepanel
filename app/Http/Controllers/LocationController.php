<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('film')->latest()->paginate(10);
        $films = Film::all();
        $locationData = $locations->keyBy('id')->map(fn($l) => [
            'name' => $l->name,
            'film_id' => $l->film_id,
            'type' => $l->type,
            'address' => $l->address,
            'start_date' => $l->start_date?->format('Y-m-d'),
            'end_date' => $l->end_date?->format('Y-m-d'),
            'rental_cost' => $l->rental_cost,
            'status' => $l->status,
            'notes' => $l->notes,
        ]);
        return view('pages.locations.index', compact('locations', 'films', 'locationData'));
    }

    public function create()
    {
        return redirect()->route('locations.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'address' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'rental_cost' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $location = Location::create($validated);

        ActivityLog::create([
            'description' => 'Lokasi "' . $location->name . '" ditambahkan',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'location',
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function show(Location $location)
    {
        return response()->json($location);
    }

    public function edit(Location $location)
    {
        return response()->json($location);
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'address' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'rental_cost' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $location->update($validated);

        ActivityLog::create([
            'description' => 'Lokasi "' . $location->name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'location',
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        $name = $location->name;
        $location->delete();

        ActivityLog::create([
            'description' => 'Lokasi "' . $name . '" dihapus',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'location',
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }
}
