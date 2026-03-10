<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\MyCarPdfController;
use App\Http\Controllers\AdminController;

Route::get('/', [CarController::class, 'index'])->name('home');
Route::get('/showcar/{car}', [CarController::class, 'show'])->name('car.show');

Route::middleware('auth')->group(function () {
    Route::get('/offercar', [CarController::class, 'create'])->name('offercar'); 
    Route::post('/offercar/addcar', [CarController::class, 'create_step1'])->name('offercar.step1');
    Route::get('/offercar/addcar/{license_plate}', [CarController::class, 'create_step2'])->name('offercar.step2');
    Route::get('/offercar/tags/{car}', [CarController::class, 'create_step3'])->name('offercar.step3');
    Route::post('/offercar/store', [CarController::class, 'store'])->name('offercar.store');
    Route::post('/offercar/store_tags', [CarController::class, 'store_tags'])->name('offercar.store_tags');
    Route::get('/mycars', [CarController::class, 'showmycars'])->name('cars.mycars');
    Route::get('/mycars/{car}/tags', [CarController::class, 'edit_tags'])->name('cars.tags.edit');
    Route::delete('/mycars/{car}/delete', [CarController::class, 'destroy'])->name('cars.destroy');
    Route::get('/mycars/{car}/pdf', [MyCarPdfController::class, 'download'])->name('cars.pdf');
});

Route::middleware('auth')->get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

require __DIR__.'/auth.php';
