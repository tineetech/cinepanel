<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::latest()->paginate(20);
        return view('pages.activity-logs.index', compact('activityLogs'));
    }

    public function store(Request $request)
    {
        return redirect()->route('activity-logs.index');
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return redirect()->route('activity-logs.index')->with('success', 'Log aktivitas berhasil dihapus!');
    }
}
