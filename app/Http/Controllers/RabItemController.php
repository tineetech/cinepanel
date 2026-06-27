<?php

namespace App\Http\Controllers;

use App\Models\RabItem;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RabItemController extends Controller
{
    public function index()
    {
        $rabItems = RabItem::with('film')->latest()->paginate(10);
        $films = Film::all();
        $rabItemData = $rabItems->keyBy('id')->map(fn($i) => [
            'name' => $i->name,
            'film_id' => $i->film_id,
            'category' => $i->category,
            'quantity' => $i->quantity,
            'unit' => $i->unit,
            'unit_price' => $i->unit_price,
            'total_price' => $i->total_price,
            'status' => $i->status,
            'notes' => $i->notes,
        ]);
        return view('pages.rab-items.index', compact('rabItems', 'films', 'rabItemData'));
    }

    public function create()
    {
        return redirect()->route('rab-items.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $rabItem = RabItem::create($validated);

        ActivityLog::create([
            'description' => 'Item RAB "' . $rabItem->name . '" ditambahkan',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'rab-item',
        ]);

        return redirect()->route('rab-items.index')->with('success', 'Item RAB berhasil ditambahkan!');
    }

    public function show(RabItem $rabItem)
    {
        return response()->json($rabItem);
    }

    public function edit(RabItem $rabItem)
    {
        return response()->json($rabItem);
    }

    public function update(Request $request, RabItem $rabItem)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $rabItem->update($validated);

        ActivityLog::create([
            'description' => 'Item RAB "' . $rabItem->name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'rab-item',
        ]);

        return redirect()->route('rab-items.index')->with('success', 'Item RAB berhasil diperbarui!');
    }

    public function destroy(RabItem $rabItem)
    {
        $name = $rabItem->name;
        $rabItem->delete();

        ActivityLog::create([
            'description' => 'Item RAB "' . $name . '" dihapus',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'rab-item',
        ]);

        return redirect()->route('rab-items.index')->with('success', 'Item RAB berhasil dihapus!');
    }
}
