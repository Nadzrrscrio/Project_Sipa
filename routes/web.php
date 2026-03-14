<?php

use App\Http\Controllers\AlumniTrackingController;
use App\Models\Alumni;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $alumnis = Alumni::with('trackingLogs')->get();
    return view('dashboard', compact('alumnis'));
})->name('dashboard');

Route::post('/track/{id}/{type?}', [AlumniTrackingController::class, 'trackAlumni'])->name('alumni.track');

Route::post('/reset/{id}', [AlumniTrackingController::class, 'resetTracking'])->name('alumni.reset');

Route::post('/reset-all', [AlumniTrackingController::class, 'resetAllTracking'])->name('alumni.reset_all');
