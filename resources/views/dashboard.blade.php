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

    {{-- Soil Moisture --}}
    <div class="col-md-3">
        <x-adminlte-info-box title="Kelembapan Baju" text="{{ $data['soil'] }}%" icon="fas fa-tshirt" theme="green"/>
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

        <form id="servoForm" action="/servo/toggle" method="POST" style="display:inline-block;">
            @csrf
            <button id="servoBtn" type="button" class="btn btn-primary">
                <i class="fas fa-cog"></i> Panaskan Jemuran
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

{{-- GRAFIK SOIL --}}
<div class="card mt-4">
    <div class="card-header">
        <h4>Grafik Kelembapan Tanah (Setiap 30 Menit)</h4>
    </div>
    <div class="card-body">
            <div class="chart-container">
                <canvas id="chartSoil"></canvas>
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
const soil = [45, 50, 55, 52, 48, 46];
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

// ------- GRAFIK SOIL -------
new Chart(document.getElementById('chartSoil'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Kelembapan Tanah (%)',
            data: soil,
            borderWidth: 2,
            tension: 0.25,
            fill: false,
            borderColor: 'rgba(139,69,19,1)',
            backgroundColor: 'rgba(139,69,19,0.12)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(139,69,19,1)'
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

// ------- SERVO TOGGLE -------
let servoActive = false;
document.getElementById('servoBtn').addEventListener('click', function() {
    servoActive = !servoActive;
    this.innerHTML = servoActive ? '<i class="fas fa-cog"></i> Teduhkan Jemuran' : '<i class="fas fa-cog"></i> Panaskan Jemuran';
    
    // Submit via AJAX instead of form submission
    fetch('/servo/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
});
</script>

@endsection

@stop
