<?php

use App\Http\Controllers\CompetitionsController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions', [CompetitionController::class, 'index'])->name('competition.index');
Route::get('/competition', [CompetitionController::class, 'show'])->name('competition.show');
Route::get('/matches', [CompetitionController::class, 'matches'])->name('competition.matches');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
