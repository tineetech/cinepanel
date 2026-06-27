<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(10);
        return view('pages.notifications.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'type' => 'nullable|string|max:50',
        ]);

        $validated['is_read'] = false;

        $notification = Notification::create($validated);

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dibuat!');
    }

    public function update(Request $request, Notification $notification)
    {
        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'Notifikasi ditandai sudah dibaca!');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus!');
    }
}
