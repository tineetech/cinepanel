<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('film')->latest()->paginate(10);
        $films = Film::all();
        $propertyData = $properties->keyBy('id')->map(fn($p) => [
            'nama' => $p->name,
            'kategori' => $p->category ?? '-',
            'film' => $p->film?->title ?? '-',
            'film_id' => $p->film_id,
            'jumlah' => $p->quantity ? $p->quantity . ' ' . ($p->unit ?? '') : '-',
            'quantity' => $p->quantity,
            'unit' => $p->unit ?? '',
            'estimasi' => $p->estimated_price ? 'Rp ' . number_format($p->estimated_price, 0, ',', '.') : '-',
            'estimated_price' => $p->estimated_price ?? '',
            'catatan' => $p->notes ?? '-',
            'status' => $p->status,
        ]);
        return view('pages.properties.index', compact('properties', 'films', 'propertyData'));
    }

    public function create()
    {
        return redirect()->route('properties.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'estimated_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $property = Property::create($validated);

        ActivityLog::create([
            'description' => 'Properti "' . $property->name . '" ditambahkan ke daftar properti',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'property',
        ]);

        return redirect()->route('properties.index')->with('success', 'Properti berhasil ditambahkan!');
    }

    public function show(Property $property)
    {
        $property->load('film');
        return response()->json($property);
    }

    public function edit(Property $property)
    {
        $property->load('film');
        return response()->json($property);
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'estimated_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $property->update($validated);

        ActivityLog::create([
            'description' => 'Properti "' . $property->name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'property',
        ]);

        return redirect()->route('properties.index')->with('success', 'Properti berhasil diperbarui!');
    }

    public function destroy(Property $property)
    {
        $name = $property->name;
        $property->delete();

        ActivityLog::create([
            'description' => 'Properti "' . $name . '" dihapus dari daftar properti',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'property',
        ]);

        return redirect()->route('properties.index')->with('success', 'Properti berhasil dihapus!');
    }
}
