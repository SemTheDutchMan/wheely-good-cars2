<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MyCarPdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CarController::class, 'index'])->name('home');
Route::get('/aanbod/{car}', [CarController::class, 'show'])->name('car.show');
Route::get('/aanbod/{car}/views-today', [CarController::class, 'todayViews'])->name('car.views.today');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/mijn-autos', [CarController::class, 'showMyCars'])->name('cars.mycars');
    Route::get('/auto-aanbieden', [CarController::class, 'create'])->name('offercar');
    Route::post('/auto-aanbieden/stap-1', [CarController::class, 'createStep1'])->name('offercar.step1');
    Route::get('/auto-aanbieden/{license_plate}/gegevens', [CarController::class, 'createStep2'])->name('offercar.step2');
    Route::post('/auto-aanbieden/opslaan', [CarController::class, 'store'])->name('cars.store');
    Route::get('/auto-aanbieden/{car}/tags', [CarController::class, 'createStep3'])->name('offercar.step3');
    Route::post('/auto-aanbieden/tags', [CarController::class, 'storeTags'])->name('offercar.tags.store');
    Route::get('/autos/{car}/tags/bewerken', [CarController::class, 'editTags'])->name('cars.tags.edit');
    Route::patch('/autos/{car}/tags', [CarController::class, 'updateTags'])->name('cars.tags.update');
    Route::patch('/autos/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/autos/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    Route::get('/autos/{car}/pdf', [MyCarPdfController::class, 'download'])->name('cars.pdf');

    Route::get('/beheer', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/beheer/live-dashboard', [AdminController::class, 'liveDashboard'])->name('admin.live-dashboard');
    Route::get('/beheer/live-dashboard/stats', [AdminController::class, 'stats'])->name('admin.stats');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
