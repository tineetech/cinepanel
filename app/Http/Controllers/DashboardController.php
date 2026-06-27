<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\CastMember;
use App\Models\Crew;
use App\Models\Property;
use App\Models\RabItem;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\Script;
use App\Models\ShotList;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $focusFilm = Film::where('is_focus', true)->first();
        $totalFilms = Film::count();
        $totalCast = CastMember::count();
        $totalCrew = Crew::count();
        $totalLocations = Location::count();
        $upcomingSchedules = Schedule::whereIn('status', ['Terjadwal', 'Upcoming'])
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->limit(5)
            ->get();
        $recentFilms = Film::latest()->limit(5)->get();
        $activities = ActivityLog::latest()->limit(10)->get();
        $filmProgress = Film::whereIn('status', ['Pra-Produksi', 'Produksi', 'Pasca-Produksi'])
            ->select('id', 'title', 'status')
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'title' => $f->title,
                'progress' => match ($f->status) {
                    'Pra-Produksi' => rand(10, 30),
                    'Produksi' => rand(40, 75),
                    'Pasca-Produksi' => rand(80, 95),
                    default => 0,
                },
            ]);
        $totalRab = RabItem::sum('total_price');

        return view('pages.dashboard', compact(
            'focusFilm',
            'totalFilms',
            'totalCast',
            'totalCrew',
            'totalLocations',
            'upcomingSchedules',
            'recentFilms',
            'activities',
            'filmProgress',
            'totalRab',
        ));
    }
}
