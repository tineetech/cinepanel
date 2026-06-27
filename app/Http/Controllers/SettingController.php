<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'date_format' => 'nullable|string|max:20',
            'dark_mode' => 'nullable|boolean',
            'sidebar_collapsed' => 'nullable|boolean',
            'email_notification' => 'nullable|boolean',
            'schedule_notification' => 'nullable|boolean',
            'rab_notification' => 'nullable|boolean',
            'weekly_report' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
