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
            'soil' => rand(20, 90),      // Soil moisture (dummy)
            'rain' => rand(0, 1),        // 0 = kering, 1 = ada air
        ];

        // Logika notifikasi
        $alerts = [];

        if ($data['soil'] > 70) {
            $alerts[] = "Kelembapan tanah tinggi: pakaian masih lembab!";
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
