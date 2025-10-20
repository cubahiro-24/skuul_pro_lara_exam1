<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\RendezVousController as AdminRendezVousController;
use App\Http\Controllers\Admin\PaiementController as AdminPaiementController;
use App\Http\Controllers\Patient\RendezVousController as PatientRendezVousController;
use App\Http\Controllers\Patient\PaiementController as PatientPaiementController;
use App\Http\Controllers\Medecin\RendezVousController as MedecinRendezVousController;
use Illuminate\Support\Facades\Route;

// Page d'accueil publique
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes d'authentification (Laravel Breeze)
require __DIR__.'/auth.php';

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirection après login selon le rôle
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role?->nom;

    return match ($role) {
        'Admin' => redirect()->route('admin.dashboard'),
        'Medecin' => redirect()->route('medecin.dashboard'),
        'Patient' => redirect()->route('patient.dashboard'),
        'Secretaire' => redirect()->route('admin.dashboard'),
        'Caissier' => redirect()->route('admin.paiements.index'),
        default => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes Admin
Route::middleware(['auth', 'role:Admin,Secretaire'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('utilisateurs', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('rendez-vous', AdminRendezVousController::class);
    Route::resource('paiements', AdminPaiementController::class);
});

// Routes Médecin
Route::middleware(['auth', 'role:Medecin'])->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('medecin.dashboard');
    })->name('dashboard');
    Route::resource('rendez-vous', MedecinRendezVousController::class);
});

// Routes Patient
Route::middleware(['auth', 'role:Patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');
    Route::resource('rendez-vous', PatientRendezVousController::class);
    Route::get('/paiements', [PatientPaiementController::class, 'index'])->name('paiements.index');
    Route::get('/factures', [PatientPaiementController::class, 'factures'])->name('factures.index');
});

// API Routes for AJAX calls
Route::get('/api/services/{service}/type-services', function ($serviceId) {
    return \App\Models\TypeService::where('service_id', $serviceId)->get(['id', 'nom', 'prix', 'duree_minutes']);
});
