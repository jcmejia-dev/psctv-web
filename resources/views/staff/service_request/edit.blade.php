@extends('layouts.admin')
@section('title','Edit Service Request')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <x-lucide-pencil class="w-6 h-6 text-blue-600"/> Edit Service Request #REQ-{{ $request->id }}
        </h2>
        <a href="{{ route('service_requests.show', $request->id) }}" 
           class="text-sm px-4 py-2 border rounded-lg hover:bg-gray-100 transition">← Back</a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('service_requests.update', $request->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $request->customer_name) }}" 
                       required class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $request->phone) }}" 
                       class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                   class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input type="text" id="address" name="address" value="{{ old('address', $request->address) }}" 
                   required class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <p id="addressStatus" class="text-sm text-gray-500 mt-1">Edit the address to update the map preview.</p>
        </div>

        <!-- Map Preview -->
        <div id="mapPreviewContainer" class="mt-4 h-72 rounded-xl border overflow-hidden shadow">
            <div id="mapPreview" class="w-full h-full"></div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
            <select name="service_type" required 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="Installation" @selected($request->service_type == 'Installation')>Installation</option>
                <option value="Repair" @selected($request->service_type == 'Repair')>Repair</option>
                <option value="Maintenance" @selected($request->service_type == 'Maintenance')>Maintenance</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" 
                      class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      placeholder="Add notes...">{{ old('notes', $request->notes) }}</textarea>
        </div>

        <div class="text-right">
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                💾 Update Request
            </button>
        </div>
    </form>
</div>

<!-- Leaflet Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addressInput = document.getElementById('address');
    const statusText = document.getElementById('addressStatus');
    const mapContainer = document.getElementById('mapPreviewContainer');
    let map, marker;

    // Load existing coordinates or default
    const initialLat = {{ $request->latitude ?? '0' }};
    const initialLon = {{ $request->longitude ?? '0' }};
    const hasCoords = {{ $request->latitude && $request->longitude ? 'true' : 'false' }};

    function showMap(lat, lon) {
        if (!map) {
            map = L.map('mapPreview').setView([lat, lon], hasCoords ? 15 : 3);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            marker = L.marker([lat, lon]).addTo(map);
        } else {
            map.setView([lat, lon], 15);
            marker.setLatLng([lat, lon]);
        }
    }

    // Initialize existing map position
    showMap(initialLat || 14.5995, initialLon || 120.9842); // Default: Manila

    // Auto-update map when address changes
    let typingTimer;
    const delay = 1000;
    addressInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(async () => {
            const query = addressInput.value.trim();
            if (!query) return;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                const data = await response.json();
                if (data.length) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    statusText.textContent = "✅ Address found. Map preview updated.";
                    showMap(lat, lon);
                } else {
                    statusText.textContent = "❌ Address not found. Try again.";
                }
            } catch (error) {
                console.error(error);
                statusText.textContent = "⚠️ Error fetching address. Please try again.";
            }
        }, delay);
    });
});
</script>
@endsection
