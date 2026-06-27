<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Film;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('film')->latest()->paginate(10);
        $films = Film::all();
        $scheduleData = $schedules->keyBy('id')->map(fn($s) => [
            'activity_name' => $s->activity_name,
            'film_id' => $s->film_id,
            'activity_type' => $s->activity_type,
            'date' => $s->date?->format('Y-m-d'),
            'start_time' => $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '',
            'end_time' => $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '',
            'location' => $s->location,
            'pic' => $s->pic,
            'attendees' => $s->attendees,
            'discussion_materials' => $s->discussion_materials,
            'status' => $s->status,
            'description' => $s->description,
        ]);
        return view('pages.schedules.index', compact('schedules', 'films', 'scheduleData'));
    }

    public function create()
    {
        return redirect()->route('schedules.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'activity_name' => 'required|string|max:255',
            'activity_type' => 'required|string|max:100',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'discussion_materials' => 'nullable|string',
            'status' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $schedule = Schedule::create($validated);

        ActivityLog::create([
            'description' => 'Jadwal "' . $schedule->activity_name . '" ditambahkan',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'schedule',
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function show(Schedule $schedule)
    {
        return response()->json($schedule);
    }

    public function edit(Schedule $schedule)
    {
        return response()->json($schedule);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'activity_name' => 'required|string|max:255',
            'activity_type' => 'required|string|max:100',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'discussion_materials' => 'nullable|string',
            'status' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $schedule->update($validated);

        ActivityLog::create([
            'description' => 'Jadwal "' . $schedule->activity_name . '" diperbarui',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'schedule',
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $name = $schedule->activity_name;
        $schedule->delete();

        ActivityLog::create([
            'description' => 'Jadwal "' . $name . '" dihapus',
            'user_name' => auth()->user()->name ?? 'System',
            'type' => 'schedule',
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
