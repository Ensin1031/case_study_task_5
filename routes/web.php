<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', [TravelController::class, 'index'])->name('home');
    Route::get('/travels', [TravelController::class, 'common_index'])->name('travels.travels');
    Route::get('/user-travels', [TravelController::class, 'user_index'])->name('travels.user-travels');
    Route::get('/travels/{id}', [TravelController::class, 'show'])->name('travels.travel-show');
});

Route::middleware('auth')->group(function () {
    Route::post('/travels/create', [TravelController::class, 'store'])->name('travel.create');
    Route::patch('/travels/update', [TravelController::class, 'update'])->name('travel.update');
    Route::delete('/travels/destroy', [TravelController::class, 'destroy'])->name('travel.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
