<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// tombol kontrol
Route::post('/servo/toggle', [DashboardController::class, 'toggleServo']);
Route::post('/buzzer/off', [DashboardController::class, 'turnOffBuzzer']);
Route::get('/data', [DashboardController::class, 'data']);
