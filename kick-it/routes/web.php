<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

# RoomController Routes
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::post('/create-room', [RoomController::class, 'store'])->name('rooms.store');

Route::post('/rooms/{room}/join', [RoomController::class, 'join'])->name('rooms.join');

# AuthController Routes
Route::get('/signup', [AuthController::class, 'register'])->name('auth.register');
