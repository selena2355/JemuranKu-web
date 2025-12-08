<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy sensor
        $data = [
            'temperature' => rand(25, 34),
            'humidity' => rand(60, 90),
            'gas' => rand(100, 600),     // MQ2 ppm (dummy)
            'rain' => rand(0, 1),        // 0 = kering, 1 = ada air
        ];

        // Logika notifikasi
        $alerts = [];

        if ($data['gas'] > 300) {
            $alerts[] = "Terdeteksi asap di area jemuran!";
        }

        if ($data['humidity'] > 80 && $data['temperature'] < 28) {
            $alerts[] = "Indikasi hujan: suhu turun dan kelembapan tinggi.";
        }

        if ($data['rain'] == 1) {
            $alerts[] = "Rain sensor mendeteksi tetesan air!";
        }

        return view('dashboard', compact('data', 'alerts'));
    }

    public function toggleServo()
    {
        // Nanti tinggal panggil API ke ESP32
        return back()->with('msg', 'Servo diaktifkan!');
    }

    public function turnOffBuzzer()
    {
        // Nanti tinggal panggil API IoT
        return back()->with('msg', 'Buzzer dimatikan!');
    }

    
}
