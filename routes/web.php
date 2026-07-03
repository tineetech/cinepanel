<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\CastMemberController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RabItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\ShotListController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('films', FilmController::class);
    Route::resource('cast-members', CastMemberController::class);
    Route::get('/cast-members/export/pdf', [CastMemberController::class, 'exportPdf'])->name('cast-members.export-pdf');
    Route::resource('crews', CrewController::class);
    Route::get('/crews/export/pdf', [CrewController::class, 'exportPdf'])->name('crews.export-pdf');
    Route::resource('properties', PropertyController::class);
    Route::resource('rab-items', RabItemController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('scripts', ScriptController::class);
    Route::resource('shot-lists', ShotListController::class);
    Route::get('/shot-lists/export/pdf', [ShotListController::class, 'exportPdf'])->name('shot-lists.export-pdf');
    Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'store', 'destroy']);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('notifications', NotificationController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
