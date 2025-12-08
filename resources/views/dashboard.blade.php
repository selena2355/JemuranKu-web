@extends('adminlte::page')

@section('title', 'Dashboard Jemuran IoT')

@push('css')
<style>
    .main-sidebar {
        display: none !important;
    }
    .content-wrapper {
        margin-left: 0 !important;
    }
    a[data-widget="pushmenu"] {
        display: none !important;
    }
    /* Smaller chart container so charts don't occupy full height */
    .chart-container {
        max-width: 100%;
        height: 220px; /* adjust this value to make charts taller/shorter */
        margin: 0 auto;
    }
    .chart-container canvas {
        width: 100% !important;
        height: 100% !important;
    }
</style>
@endpush

@section('content_header')
    <h1>Dashboard Monitoring</h1>
@stop

@section('content')
<div class="row">

    {{-- Suhu --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Suhu" text="{{ $data['temperature'] }}°C" icon="fas fa-thermometer-half" theme="orange"/>
    </div>

    {{-- Kelembapan --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Kelembapan" text="{{ $data['humidity'] }}%" icon="fas fa-tint" theme="blue"/>
    </div>

    {{-- Gas MQ2 --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Asap (MQ2)" text="{{ $data['gas'] }} ppm" icon="fas fa-smog" theme="red"/>
    </div>

    {{-- Rain Sensor --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Rain Detector" 
            text="{{ $data['rain'] ? 'Basah' : 'Kering' }}" 
            icon="fas fa-cloud-rain" 
            theme="{{ $data['rain'] ? 'teal' : 'light' }}"/>
    </div>

</div>

{{-- ALERT SECTION --}}
@if(!empty($alerts))
    <div class="alert alert-danger mt-3">
        <strong>Peringatan:</strong>
        <ul>
            @foreach($alerts as $alert)
                <li>{{ $alert }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- TOMBOL KONTROL --}}
<div class="card mt-4">
    <div class="card-header">
        <h4>Kontrol Aktuator</h4>
    </div>
    <div class="card-body">

        <form action="/servo/toggle" method="POST" style="display:inline-block;">
            @csrf
            <button class="btn btn-primary">
                <i class="fas fa-cog"></i> Aktifkan Servo (Jemuran Teduh)
            </button>
        </form>

        <form action="/buzzer/off" method="POST" style="display:inline-block; margin-left:10px;">
            @csrf
            <button class="btn btn-danger">
                <i class="fas fa-bell-slash"></i> Matikan Buzzer
            </button>
        </form>

    </div>
</div>

{{-- GRAFIK SUHU --}}
<div class="card mt-4">
    <div class="card-header">
        <h4>Grafik Suhu (Setiap 30 Menit)</h4>
    </div>
    <div class="card-body">
            <div class="chart-container">
                <canvas id="chartTemperature"></canvas>
            </div>
    </div>
</div>

{{-- GRAFIK KELEMBAPAN --}}
<div class="card mt-4">
    <div class="card-header">
        <h4>Grafik Kelembapan (Setiap 30 Menit)</h4>
    </div>
    <div class="card-body">
            <div class="chart-container">
                <canvas id="chartHumidity"></canvas>
            </div>
    </div>
</div>

{{-- GRAFIK GAS --}}
<div class="card mt-4">
    <div class="card-header">
        <h4>Grafik Gas MQ2 (Setiap 30 Menit)</h4>
    </div>
    <div class="card-body">
            <div class="chart-container">
                <canvas id="chartGas"></canvas>
            </div>
    </div>
</div>

{{-- GRAFIK RAIN --}}
<div class="card mt-4 mb-4">
    <div class="card-header">
        <h4>Grafik Rain Detector (Setiap 30 Menit)</h4>
    </div>
    <div class="card-body">
            <div class="chart-container">
                <canvas id="chartRain"></canvas>
            </div>
    </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ------- DATA DUMMY (Nanti tinggal diganti dari database) ------
const labels = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30"];

const temperature = [28, 29, 30, 29, 28, 27];
const humidity = [60, 62, 65, 63, 61, 60];
const gas = [200, 210, 250, 180, 190, 220];
const rain = [0, 0, 0, 1, 0, 0]; // 1 = basah, 0 = kering

// ------- GRAFIK SUHU -------
new Chart(document.getElementById('chartTemperature'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Suhu (°C)',
            data: temperature,
            borderWidth: 2,
            tension: 0.3,
            fill: true,
            borderColor: 'rgba(255,99,71,1)',
            backgroundColor: 'rgba(255,99,71,0.12)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(255,99,71,1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, labels: { usePointStyle: true } }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// ------- GRAFIK KELEMBAPAN -------
new Chart(document.getElementById('chartHumidity'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Kelembapan (%)',
            data: humidity,
            borderWidth: 2,
            tension: 0.3,
            fill: true,
            borderColor: 'rgba(54,162,235,1)',
            backgroundColor: 'rgba(54,162,235,0.12)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(54,162,235,1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, labels: { usePointStyle: true } }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// ------- GRAFIK GAS -------
new Chart(document.getElementById('chartGas'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Asap (ppm)',
            data: gas,
            borderWidth: 2,
            tension: 0.25,
            fill: false,
            borderColor: 'rgba(153,102,255,1)',
            backgroundColor: 'rgba(153,102,255,0.12)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(153,102,255,1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, labels: { usePointStyle: true } }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// ------- GRAFIK RAIN -------
new Chart(document.getElementById('chartRain'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Rain (0 = kering, 1 = basah)',
            data: rain,
            borderWidth: 2,
            tension: 0.2,
            stepped: true,
            fill: true,
            borderColor: 'rgba(20,160,120,1)',
            backgroundColor: 'rgba(20,160,120,0.12)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(20,160,120,1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, labels: { usePointStyle: true } }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>

@endsection

@stop
