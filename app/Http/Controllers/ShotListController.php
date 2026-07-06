<?php

namespace App\Http\Controllers;

use App\Models\ShotList;
use App\Models\Film;
use App\Models\Location;
use App\Models\CastMember;
use App\Models\ActivityLog;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class ShotListController extends Controller
{
    public function index()
    {
        $perPage = in_array(request('per_page'), [10, 20, 50, 100]) ? (int) request('per_page') : 50;
        $shotLists = ShotList::with(['film', 'location', 'cast'])->orderByRaw('LENGTH(scene), scene')->orderByRaw('CAST(shot_order AS UNSIGNED)')->paginate($perPage)->withQueryString();
        $films = Film::all();
        $focusFilm = Film::where('is_focus', true)->first();
        $locations = Location::all();
        $castMembers = CastMember::all();
        $shotListsData = $shotLists->keyBy('id')->map(fn($s) => [
            'film' => $s->film?->title ?? '-',
            'film_id' => $s->film_id,
            'scene' => $s->scene ?? '-',
            'shot_order' => $s->shot_order ?? '-',
            'deskripsi' => $s->shot_description ?? '-',
            'kamera' => $s->camera_type ?? '-',
            'gerakan' => $s->camera_movement ?? '-',
            'durasi' => $s->estimated_duration ?? '-',
            'lokasi' => $s->location?->name ?? '-',
            'lokasi_id' => $s->location_id,
            'status' => $s->status,
            'catatan' => $s->director_notes ?? '-',
        ]);
        return view('pages.shot-lists.index', compact('shotLists', 'films', 'focusFilm', 'locations', 'castMembers', 'shotListsData'));
    }

    public function create()
    {
        return redirect()->route('shot-lists.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'scene' => 'nullable|string|max:255',
            'shot_order' => 'nullable|integer|min:1',
            'shot_description' => 'nullable|string|max:255',
            'camera_type' => 'nullable|string|max:100',
            'camera_movement' => 'nullable|string|max:100',
            'estimated_duration' => 'nullable|string|max:50',
            'location_name' => 'nullable|string|max:255',
            'cast_id' => 'nullable|exists:cast_members,id',
            'sound' => 'nullable|array',
            'shoot_time' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'director_notes' => 'nullable|string',
        ]);

        if ($locationName = $request->input('location_name')) {
            $existing = Location::where('name', $locationName)->first();
            if ($existing) {
                $validated['location_id'] = $existing->id;
            } else {
                $validated['location_id'] = Location::create(['name' => $locationName])->id;
            }
        }
        unset($validated['location_name']);

        if (isset($validated['sound']) && is_array($validated['sound'])) {
            $validated['sound'] = json_encode($validated['sound']);
        }

        $shotList = ShotList::create($validated);

        ActivityLog::create([
            'description' => 'Shot list scene "' . ($shotList->scene ?? '-') . '" ditambahkan',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'shot-list',
        ]);

        return redirect()->route('shot-lists.index')->with('success', 'Shot list berhasil ditambahkan!');
    }

    public function show(ShotList $shotList)
    {
        $shotList->load(['film', 'location', 'cast']);
        return response()->json($shotList);
    }

    public function edit(ShotList $shotList)
    {
        $shotList->load(['film', 'location', 'cast']);
        return response()->json($shotList);
    }

    public function update(Request $request, ShotList $shotList)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'scene' => 'nullable|string|max:255',
            'shot_order' => 'nullable|integer|min:1',
            'shot_description' => 'nullable|string|max:255',
            'camera_type' => 'nullable|string|max:100',
            'camera_movement' => 'nullable|string|max:100',
            'estimated_duration' => 'nullable|string|max:50',
            'location_name' => 'nullable|string|max:255',
            'cast_id' => 'nullable|exists:cast_members,id',
            'sound' => 'nullable|array',
            'shoot_time' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'director_notes' => 'nullable|string',
        ]);

        if ($request->has('location_name')) {
            if ($locationName = $request->input('location_name')) {
                $existing = Location::where('name', $locationName)->first();
                if ($existing) {
                    $validated['location_id'] = $existing->id;
                } else {
                    $validated['location_id'] = Location::create(['name' => $locationName])->id;
                }
            } else {
                $validated['location_id'] = null;
            }
        }
        unset($validated['location_name']);

        if (isset($validated['sound']) && is_array($validated['sound'])) {
            $validated['sound'] = json_encode($validated['sound']);
        }

        $shotList->update($validated);

        ActivityLog::create([
            'description' => 'Shot list scene "' . ($shotList->scene ?? '-') . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'shot-list',
        ]);

        return redirect()->route('shot-lists.index')->with('success', 'Shot list berhasil diperbarui!');
    }

    public function exportPdf()
    {
        $shotLists = ShotList::with(['film', 'location', 'cast'])->orderByRaw('LENGTH(scene), scene')->orderByRaw('CAST(shot_order AS UNSIGNED)')->get();
        $focusFilm = Film::where('is_focus', true)->first();

        $html = view('pages.shot-lists.pdf', compact('shotLists', 'focusFilm'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="laporan-shot-list.pdf"');
    }

    public function destroy(ShotList $shotList)
    {
        $scene = $shotList->scene ?? '-';
        $shotList->delete();

        ActivityLog::create([
            'description' => 'Shot list scene "' . $scene . '" dihapus',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'shot-list',
        ]);

        return redirect()->route('shot-lists.index')->with('success', 'Shot list berhasil dihapus!');
    }
}
