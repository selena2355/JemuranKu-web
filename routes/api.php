<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Dummy sensor data endpoint
Route::get('/sensor', function () {
    return response()->json([
        'temperature' => rand(20, 35),
        'humidity' => rand(30, 90),
        'soil' => rand(20, 90),
        'rain' => rand(0, 1),
    ]);
});

// Dummy chart data endpoint
Route::get('/chart', function () {
    return response()->json([
        'labels' => ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30"],
        'temperature' => [28, 29, 30, 29, 28, 27],
        'humidity' => [60, 62, 65, 63, 61, 60],
        'soil' => [45, 50, 55, 52, 48, 46],
        'rain' => [0, 0, 0, 1, 0, 0]
    ]);
});

// Servo toggle endpoint
Route::post('/servo/toggle', function () {
    return response()->json(['status' => 'servo toggled']);
});

// Buzzer off endpoint
Route::post('/buzzer/off', function () {
    return response()->json(['status' => 'buzzer off']);
});
