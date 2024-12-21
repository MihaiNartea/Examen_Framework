<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;

Route::resource('events', EventController::class);
Route::get('events/{eventId}/register', [ParticipantController::class, 'create'])->name('participants.create');
Route::post('events/{eventId}/register', [ParticipantController::class, 'store'])->name('participants.store');

