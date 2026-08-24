<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuiviLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RendezvousController;
use App\Http\Controllers\SymptomLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DisponibiliteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // --- Profil ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rendez-vous ---
    Route::get('/rendezvous', [RendezvousController::class, 'index'])->name('rendezvous.index');
    Route::get('/rendezvous/create', [RendezvousController::class, 'create'])->name('rendezvous.create');
    Route::post('/rendezvous', [RendezvousController::class, 'store'])->name('rendezvous.store');
    Route::patch('/rendezvous/{rendezvous}/status', [RendezvousController::class, 'updateStatus'])->name('rendezvous.status');

    // --- Notifications ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // --- Symptômes ---
    Route::get('/symptoms', [SymptomLogController::class, 'index'])->name('symptoms.index');
    Route::get('/symptoms/create', [SymptomLogController::class, 'create'])->name('symptoms.create');
    Route::post('/symptoms', [SymptomLogController::class, 'store'])->name('symptoms.store');
    Route::get('/symptoms/{symptomLog}', [SymptomLogController::class, 'show'])->name('symptoms.show');
    Route::get('/patients/{patient}/symptoms', [SymptomLogController::class, 'patientLogs'])->name('symptoms.patient');
    Route::post('/symptoms/{symptomLog}/comment', [SymptomLogController::class, 'addComment'])->name('symptoms.comment');

    // --- Suivi ---
    Route::get('/patients/{patient}/suivi', [SuiviLogController::class, 'index'])->name('suivi.index');
    Route::get('/patients/{patient}/suivi/create', [SuiviLogController::class, 'create'])->name('suivi.create');
    Route::post('/patients/{patient}/suivi', [SuiviLogController::class, 'store'])->name('suivi.store');

    // --- Disponibilités ---
    Route::get('/disponibilites', [DisponibiliteController::class, 'index'])->name('disponibilites.index');
    Route::get('/disponibilites/create', [DisponibiliteController::class, 'create'])->name('disponibilites.create');
    Route::post('/disponibilites', [DisponibiliteController::class, 'store'])->name('disponibilites.store');
    Route::delete('/disponibilites/{disponibilite}', [DisponibiliteController::class, 'destroy'])->name('disponibilites.destroy');
    Route::get('/api/creneaux', [DisponibiliteController::class, 'creneaux'])->name('api.creneaux');
    Route::get('/api/jours-dispo', [DisponibiliteController::class, 'joursDispo'])->name('api.jours-dispo');

    // --- Espace Admin ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
        Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
        Route::patch('/doctors/{user}/status', [AdminController::class, 'updateDoctorStatus'])->name('doctors.status');
    });
});

require __DIR__.'/auth.php';