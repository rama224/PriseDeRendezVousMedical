<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RendezVousController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('rendez-vous.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

    Route::get('/rendez-vous', [RendezVousController::class, 'index'])->name('rendez-vous.index');
    Route::get('/rendez-vous/nouveau', [RendezVousController::class, 'create'])->name('rendez-vous.create');
    Route::get('/rendez-vous/confirmer', [RendezVousController::class, 'confirmer'])->name('rendez-vous.confirmer');
    Route::post('/rendez-vous', [RendezVousController::class, 'store'])->name('rendez-vous.store');
    Route::patch('/rendez-vous/{rendezVous}/annuler', [RendezVousController::class, 'annuler'])->name('rendez-vous.annuler');
});
