<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\TravelEventController;
use App\Http\Controllers\TravelImageController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', [TravelController::class, 'index'])->name('home');
    Route::get('/travels', [TravelController::class, 'common_index'])->name('travels.travels');
    Route::get('/user-travels', [TravelController::class, 'user_index'])->name('travels.user-travels');
    Route::get('/travels/{id}', [TravelController::class, 'show'])->name('travels.travel-show');
});

Route::middleware('auth')->group(function () {
    Route::post('/travels/create', [TravelController::class, 'store'])->name('travel.create');
    Route::patch('/travels/change-main-photo', [TravelController::class, 'change_main_photo'])->name('travel.change-main-photo');
    Route::patch('/travels/close', [TravelController::class, 'update_travel_dates'])->name('travel.update-travel-dates');
    Route::patch('/travels/update', [TravelController::class, 'update'])->name('travel.update');
    Route::delete('/travels/destroy', [TravelController::class, 'destroy'])->name('travel.destroy');

    Route::post('/travel-events/create', [TravelEventController::class, 'store'])->name('travel-events.create');
    Route::patch('/travel-events/update', [TravelEventController::class, 'update'])->name('travel-events.update');
    Route::delete('/travel-events/destroy', [TravelEventController::class, 'destroy'])->name('travel-events.destroy');

    Route::post('/travel-images/create', [TravelImageController::class, 'store'])->name('travel-images.create');
    Route::patch('/travel-images/update', [TravelImageController::class, 'update'])->name('travel-images.update');
    Route::delete('/travel-images/destroy', [TravelImageController::class, 'destroy'])->name('travel-images.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
