<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

# RoomController Routes

Route::middleware(['auth'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

    Route::post('/create-room', [RoomController::class, 'store'])->name('rooms.store');

    Route::post('/rooms/{room}/join', [RoomController::class, 'join'])->name('rooms.join');

    Route::post('rooms/{room}/leave', [RoomController::class, 'leave'])->name('rooms.leave');
});

# AuthController Routes
Route::get('/signup', [AuthController::class, 'register'])->name('auth.register');

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/signup', [AuthController::class, 'store'])->name('auth.store');

Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
