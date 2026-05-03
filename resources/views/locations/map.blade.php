@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md">
    <h2 class="text-xl font-bold mb-4">{{ $title }}</h2>
    <div id="map" style="width:100%;height:400px;"></div>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([{{ $latitude }}, {{ $longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
</script>
@endsection
