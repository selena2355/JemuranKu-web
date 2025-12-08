<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Dummy sensor data endpoint
Route::get('/sensor', function () {
    return response()->json([
        'temperature' => rand(20, 35),
        'humidity' => rand(30, 90),
        'gas' => rand(0, 1000),
        'rain' => rand(0, 1),
    ]);
});

// Dummy chart data endpoint
Route::get('/chart', function () {
    return response()->json([
        ['time' => '10:00', 'temperature' => 25],
        ['time' => '11:00', 'temperature' => 27],
        ['time' => '12:00', 'temperature' => 30],
        ['time' => '13:00', 'temperature' => 32],
        ['time' => '14:00', 'temperature' => 31],
        ['time' => '15:00', 'temperature' => 28],
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
