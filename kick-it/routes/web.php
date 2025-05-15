<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

# AuthController Routes

Route::get('/signup', [AuthController::class, 'register'])->name('auth.register');
