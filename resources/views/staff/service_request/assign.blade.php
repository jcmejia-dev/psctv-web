@extends('layouts.staff')

@section('title', 'Assign Technician')

@section('content')
<div class="max-w-6xl mx-auto py-8 space-y-8">

    <!-- Back -->
    <div>
        <a href="{{ route('staff.service_requests.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
            ← Back to Requests
        </a>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <x-lucide-user-plus class="w-6 h-6 text-purple-600"/>
            Assign Technician — <span class="text-gray-500">REQ-{{ $serviceRequest->id }}</span>
        </h1>
        <span class="px-4 py-1 rounded-full text-sm font-semibold 
            @if($serviceRequest->status=='pending') bg-yellow-100 text-yellow-700
            @elseif($serviceRequest->status=='assigned') bg-purple-100 text-purple-700
            @elseif($serviceRequest->status=='in-progress') bg-blue-100 text-blue-700
            @elseif($serviceRequest->status=='completed') bg-green-100 text-green-700
            @endif">
            {{ ucfirst($serviceRequest->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Request Info -->
        <div class="bg-white p-6 rounded-xl shadow border space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <x-lucide-info class="w-5 h-5 text-blue-500"/> Request Details
            </h2>
            <ul class="space-y-2 text-gray-700">
                <li><span class="font-medium">👤 Customer:</span> {{ $serviceRequest->customer_name }}</li>
                <li><span class="font-medium">📞 Phone:</span> {{ $serviceRequest->phone ?? 'N/A' }}</li>
                <li><span class="font-medium">📍 Address:</span> {{ $serviceRequest->address ?? 'N/A' }}</li>
                <li><span class="font-medium">🛠 Service Type:</span> {{ $serviceRequest->service_type }}</li>
                <li><span class="font-medium">📝 Notes:</span> {{ $serviceRequest->notes ?? '—' }}</li>
            </ul>
        </div>

        <!-- Assignment Panel -->
        <div class="bg-white p-6 rounded-xl shadow border relative">
            <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <x-lucide-user-check class="w-5 h-5 text-purple-600"/> Technician Assignment
            </h2>

            <form id="assignForm" method="POST" action="{{ route('staff.service_requests.assign', $serviceRequest->id) }}">
                @csrf

                <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Select Technician</label>
                <select name="technician_id" id="technician_id" required
                        class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-600">
                    <option value="">-- Choose Technician --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected($serviceRequest->technician_id == $tech->id)>
                            {{ $tech->full_name }} — {{ ucfirst($tech->status) }} ({{ $tech->active_jobs_count }} active)
                        </option>
                    @endforeach
                </select>

                @error('technician_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-3 mt-5">
                    <a href="{{ route('staff.service_requests.show', $serviceRequest->id) }}" 
                       class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                        Cancel
                    </a>
                    <button type="button" onclick="openModal()" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Assign Technician
                    </button>
                </div>
            </form>

            <!-- Confirmation Modal -->
            <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-[9999]">
                <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md animate-fadeIn z-[10000]">
                    <h2 class="text-lg font-semibold text-gray-800">Confirm Assignment</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Are you sure you want to assign this technician to
                        <strong>REQ-{{ $serviceRequest->id }}</strong>?
                    </p>

                    <div class="mt-5 flex justify-end gap-3">
                        <button onclick="closeModal()" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button id="modalConfirmBtn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="bg-white rounded-xl shadow border h-96 overflow-hidden mt-6">
        <h2 class="p-4 font-semibold text-gray-700 flex items-center gap-2 border-b">
            <x-lucide-map-pin class="w-5 h-5 text-red-600"/> Technician → Customer Route
        </h2>
        <div id="assignMap" class="w-full h-full"></div>
    </div>
</div>

<!-- Leaflet & Routing -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
function openModal() { document.getElementById('confirmModal').classList.remove('hidden'); }
function closeModal() { document.getElementById('confirmModal').classList.add('hidden'); }

// Initialize map
const custLat = parseFloat(@json($serviceRequest->latitude)) || 12.8797;
const custLon = parseFloat(@json($serviceRequest->longitude)) || 121.7740;
const custName = @json($serviceRequest->customer_name);

const map = L.map('assignMap').setView([custLat, custLon], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

const custMarker = L.marker([custLat, custLon])
    .addTo(map)
    .bindPopup(`<strong>${custName}</strong><br>Customer Location`)
    .openPopup();

let routingControl = null;
let techMarker = null;

// Draw route between technician and customer
function drawRoute(techLat, techLon, techName) {
    if (routingControl) map.removeControl(routingControl);
    if (techMarker) map.removeLayer(techMarker);

    techMarker = L.marker([techLat, techLon])
        .addTo(map)
        .bindPopup(`<strong>${techName}</strong><br>Technician Location`)
        .openPopup();

    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(techLat, techLon),
            L.latLng(custLat, custLon)
        ],
        routeWhileDragging: false,
        draggableWaypoints: false,
        addWaypoints: false,
        showAlternatives: false,
        lineOptions: {
            styles: [{ color: 'purple', opacity: 0.7, weight: 5 }]
        }
    }).addTo(map);
}

// When technician selected, fetch coordinates
document.getElementById('technician_id').addEventListener('change', function() {
    const techId = this.value;
    if (!techId) return;

    fetch(`/technicians/${techId}/location`)
        .then(res => res.json())
        .then(data => {
            if (data.latitude && data.longitude) {
                drawRoute(data.latitude, data.longitude, data.full_name ?? 'Technician');
            } else {
                alert('⚠️ This technician has no location data.');
            }
        })
        .catch(err => console.error('Fetch error:', err));
});

// Modal confirm button
document.getElementById('modalConfirmBtn').addEventListener('click', function() {
    document.getElementById('assignForm').submit();
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out forwards; }
</style>

@endsection