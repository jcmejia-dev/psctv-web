@extends('layouts.admin')

@section('title', 'Technician Route')

@section('content')
<div class="max-w-6xl mx-auto py-8 space-y-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        Technician Route — REQ-{{ $serviceRequest->id }}
    </h1>

    <div id="map" class="w-full h-96 rounded-xl shadow border"></div>

</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Leaflet Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
const techLat = parseFloat(@json($technicianLat));
const techLon = parseFloat(@json($technicianLon));
const custLat = parseFloat(@json($customerLat));
const custLon = parseFloat(@json($customerLon));

const map = L.map('map').setView([ (techLat + custLat)/2, (techLon + custLon)/2 ], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Technician marker
const techMarker = L.marker([techLat, techLon], {
    icon: L.icon({iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149071.png', iconSize: [30,30]})
}).addTo(map).bindPopup('<strong>Technician</strong>').openPopup();

// Customer marker
const custMarker = L.marker([custLat, custLon], {
    icon: L.icon({iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149060.png', iconSize: [30,30]})
}).addTo(map).bindPopup('<strong>Customer</strong>');

// Routing
L.Routing.control({
    waypoints: [
        L.latLng(techLat, techLon),
        L.latLng(custLat, custLon)
    ],
    routeWhileDragging: false,
    draggableWaypoints: false,
    addWaypoints: false,
    showAlternatives: false,
    lineOptions: {
        styles: [{color: 'purple', opacity: 0.7, weight: 5}]
    },
    createMarker: function(i, wp, n) {
        if (i === 0) return techMarker;
        if (i === n - 1) return custMarker;
        return null;
    }
}).addTo(map);
</script>
@endsection
