@extends('layouts.admin')

@section('title', 'Assign Technician')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-8 py-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                            <x-lucide-user-plus class="w-6 h-6 text-white"/>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Assign Technician</h1>
                            <p class="text-purple-100 mt-1">REQ-{{ $serviceRequest->id }} • Select the best technician for this job</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('service_requests.index') }}"
                           class="group flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm">
                            <x-lucide-arrow-left class="w-4 h-4 transition-transform group-hover:-translate-x-1"/>
                            <span>Back to Requests</span>
                        </a>
                        <span class="px-4 py-2 rounded-xl text-sm font-semibold backdrop-blur-sm flex items-center gap-2
                            @if($serviceRequest->status=='pending') bg-yellow-500/20 text-yellow-100
                            @elseif($serviceRequest->status=='assigned') bg-purple-500/20 text-purple-100
                            @elseif($serviceRequest->status=='in-progress') bg-blue-500/20 text-blue-100
                            @elseif($serviceRequest->status=='completed') bg-green-500/20 text-green-100
                            @endif">
                            <x-lucide-circle class="w-2 h-2 animate-pulse"/>
                            {{ ucfirst($serviceRequest->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Request Details & Technician Selection -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Request Details Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <x-lucide-clipboard-list class="w-5 h-5 text-blue-600"/>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Request Details</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-blue-50">
                                    <x-lucide-user class="w-4 h-4 text-blue-600"/>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Customer</p>
                                    <p class="text-gray-900 font-semibold">{{ $serviceRequest->customer_name }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-green-50">
                                    <x-lucide-phone class="w-4 h-4 text-green-600"/>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Phone</p>
                                    <p class="text-gray-900">{{ $serviceRequest->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-purple-50">
                                    <x-lucide-wrench class="w-4 h-4 text-purple-600"/>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Service Type</p>
                                    <p class="text-gray-900">{{ $serviceRequest->service_type }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-lg bg-orange-50 mt-0.5">
                                    <x-lucide-map-pin class="w-4 h-4 text-orange-600"/>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500">Address</p>
                                    <p class="text-gray-900">{{ $serviceRequest->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($serviceRequest->notes)
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <x-lucide-file-text class="w-4 h-4 text-gray-400"/>
                            <p class="text-sm font-medium text-gray-500">Additional Notes</p>
                        </div>
                        <p class="text-gray-700 text-sm">{{ $serviceRequest->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Technician Selection Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-lg bg-purple-50">
                            <x-lucide-user-check class="w-5 h-5 text-purple-600"/>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Select Technician</h2>
                    </div>

                    <form id="assignForm" method="POST" action="{{ route('service_requests.assign', $serviceRequest->id) }}">
                        @csrf

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="technician_id" class="block text-sm font-medium text-gray-700">Choose Technician</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-purple-600">
                                        <x-lucide-users class="w-4 h-4 text-gray-400"/>
                                    </div>
                                    <select name="technician_id" id="technician_id" required
                                            class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                        <option value="">-- Select a Technician --</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}" @selected($serviceRequest->technician_id == $tech->id)
                                                    data-lat="{{ $tech->latitude }}"
                                                    data-lon="{{ $tech->longitude }}"
                                                    data-name="{{ $tech->full_name }}"
                                                    data-specialization="{{ $tech->specialization ?? 'General Technician' }}"
                                                    data-active-jobs="{{ $tech->active_jobs_count }}"
                                                    data-status="{{ $tech->status }}">
                                                {{ $tech->full_name }} • {{ ucfirst($tech->status) }} • {{ $tech->active_jobs_count }} active jobs
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <x-lucide-chevron-down class="w-4 h-4 text-gray-400"/>
                                    </div>
                                </div>
                                @error('technician_id')
                                    <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4"/>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Technician Preview -->
                            <div id="technicianPreview" class="hidden p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl border border-purple-200 transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg" id="techInitials">
                                        TN
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900" id="techName">Technician Name</h3>
                                        <p class="text-sm text-gray-600" id="techSpecialization">Specialization</p>
                                        <div class="flex items-center gap-4 mt-2 text-xs">
                                            <span class="flex items-center gap-1" id="techStatus">
                                                <x-lucide-circle class="w-2 h-2 fill-current"/>
                                                Status
                                            </span>
                                            <span class="flex items-center gap-1" id="techJobs">
                                                <x-lucide-briefcase class="w-3 h-3"/>
                                                <span id="jobsCount">0</span> active jobs
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('service_requests.show', $serviceRequest->id) }}" 
                                   class="group flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <x-lucide-x class="w-4 h-4"/>
                                    Cancel
                                </a>
                                <button type="button" onclick="openModal()" 
                                        class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="assignButton" disabled>
                                    <x-lucide-user-check class="w-4 h-4 transition-transform group-hover:scale-110"/>
                                    Assign Technician
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Map -->
            <div class="space-y-8">
                <!-- Map Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                            <x-lucide-map-pin class="w-5 h-5 text-red-600"/>
                            Route Preview
                        </h2>
                    </div>
                    <div class="relative">
                        <div id="assignMap" class="w-full h-96"></div>
                        <div id="mapLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-b-2xl z-1001 hidden">
                            <div class="text-center">
                                <div class="w-8 h-8 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                                <p class="text-purple-600 font-medium">Loading route...</p>
                            </div>
                        </div>
                        <div id="noSelection" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-b-2xl z-1000">
                            <div class="text-center p-6">
                                <x-lucide-users class="w-12 h-12 text-gray-300 mx-auto mb-3"/>
                                <p class="text-gray-500 font-medium">Select a technician to view route</p>
                                <p class="text-gray-400 text-sm mt-1">The map will show the optimal route</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t bg-gray-50">
                        <div id="routeInfo" class="text-sm text-gray-600">
                            Distance and time will appear here
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-green-50">
                            <x-lucide-bar-chart-3 class="w-5 h-5 text-green-600"/>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Technician Stats</h2>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total Available:</span>
                            <span class="font-semibold text-gray-900">{{ $technicians->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Available Now:</span>
                            <span class="font-semibold text-green-600">
                                {{ $technicians->where('status', 'available')->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">On Duty:</span>
                            <span class="font-semibold text-blue-600">
                                {{ $technicians->where('status', 'on-duty')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-[9999] transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all duration-300 scale-95"
         id="modalContent">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 rounded-lg bg-purple-50">
                <x-lucide-user-check class="w-5 h-5 text-purple-600"/>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Confirm Assignment</h2>
        </div>
        
        <p class="text-gray-600 mb-2">You're about to assign:</p>
        <div class="bg-gray-50 p-4 rounded-xl mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold" id="modalTechInitials">
                    TN
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900" id="modalTechName">Technician Name</h3>
                    <p class="text-sm text-gray-600" id="modalTechDetails">Details</p>
                </div>
            </div>
        </div>
        
        <p class="text-gray-600">
            to service request <strong>REQ-{{ $serviceRequest->id }}</strong> for <strong>{{ $serviceRequest->customer_name }}</strong>.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button onclick="closeModal()" 
                    class="group flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300">
                <x-lucide-x class="w-4 h-4"/>
                Cancel
            </button>
            <button id="modalConfirmBtn" 
                    class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                <x-lucide-check class="w-4 h-4"/>
                Confirm Assignment
            </button>
        </div>
    </div>
</div>

<!-- Leaflet & Routing -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fadeIn { animation: fadeIn 0.3s ease-out; }
.animate-slideIn { animation: slideIn 0.3s ease-out; }

.technician-marker {
    background: none !important;
    border: none !important;
}

.route-popup .leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
</style>

<script>
// Modal functions
function openModal() {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.add('animate-slideIn');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('modalContent');
    content.classList.remove('animate-slideIn');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Initialize map
const custLat = parseFloat(@json($serviceRequest->latitude)) || 12.8797;
const custLon = parseFloat(@json($serviceRequest->longitude)) || 121.7740;
const custName = @json($serviceRequest->customer_name);

const map = L.map('assignMap', { zoomControl: false }).setView([custLat, custLon], 13);
L.control.zoom({ position: 'topright' }).addTo(map);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Custom icons
const customerIcon = L.divIcon({
    className: 'technician-marker',
    html: `<div class="bg-red-500 p-3 rounded-full border-4 border-white shadow-lg">
             <x-lucide-user class="w-4 h-4 text-white"/>
           </div>`,
    iconSize: [40, 40],
    iconAnchor: [20, 40]
});

const technicianIcon = L.divIcon({
    className: 'technician-marker',
    html: `<div class="bg-blue-500 p-3 rounded-full border-4 border-white shadow-lg">
             <x-lucide-wrench class="w-4 h-4 text-white"/>
           </div>`,
    iconSize: [40, 40],
    iconAnchor: [20, 40]
});

const custMarker = L.marker([custLat, custLon], { icon: customerIcon })
    .addTo(map)
    .bindPopup(`<div class="text-sm font-semibold">${custName}</div><div class="text-xs text-gray-600">Customer Location</div>`)
    .openPopup();

let routingControl = null;
let techMarker = null;

// Show/hide loading and selection states
function showMapLoading() {
    document.getElementById('mapLoading').classList.remove('hidden');
    document.getElementById('noSelection').classList.add('hidden');
}

function hideMapLoading() {
    document.getElementById('mapLoading').classList.add('hidden');
}

function showNoSelection() {
    document.getElementById('noSelection').classList.remove('hidden');
}

function hideNoSelection() {
    document.getElementById('noSelection').classList.add('hidden');
}

// Draw route between technician and customer
function drawRoute(techLat, techLon, techName, techSpecialization) {
    showMapLoading();
    
    // Remove existing route and marker
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
    if (techMarker) {
        map.removeLayer(techMarker);
        techMarker = null;
    }

    // Add technician marker
    techMarker = L.marker([techLat, techLon], { icon: technicianIcon })
        .addTo(map)
        .bindPopup(`<div class="text-sm font-semibold">${techName}</div><div class="text-xs text-gray-600">${techSpecialization}</div>`);

    // Calculate bounds to fit both markers
    const group = new L.featureGroup([custMarker, techMarker]);
    map.fitBounds(group.getBounds().pad(0.1));

    // Add routing control
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
            styles: [{ color: '#8b5cf6', opacity: 0.8, weight: 6 }]
        },
        createMarker: () => null
    }).addTo(map);

    routingControl.on('routesfound', function(e) {
        hideMapLoading();
        if (e.routes && e.routes.length > 0) {
            const route = e.routes[0];
            const distance = (route.summary.totalDistance / 1000).toFixed(1);
            const duration = Math.round(route.summary.totalTime / 60);
            
            document.getElementById('routeInfo').innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1">
                        <x-lucide-map-pin class="w-4 h-4 text-purple-600"/>
                        <span class="font-medium">${distance} km</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <x-lucide-clock class="w-4 h-4 text-blue-600"/>
                        <span class="font-medium">${duration} min</span>
                    </span>
                </div>
            `;
        }
    });

    routingControl.on('routingerror', function(e) {
        hideMapLoading();
        document.getElementById('routeInfo').innerHTML = '<span class="text-red-600">Unable to calculate route</span>';
    });
}

// Technician selection handler
document.getElementById('technician_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const assignButton = document.getElementById('assignButton');
    const preview = document.getElementById('technicianPreview');
    
    if (this.value) {
        // Enable assign button
        assignButton.disabled = false;
        
        // Show technician preview
        const techName = selectedOption.getAttribute('data-name');
        const specialization = selectedOption.getAttribute('data-specialization');
        const activeJobs = selectedOption.getAttribute('data-active-jobs');
        const status = selectedOption.getAttribute('data-status');
        const lat = selectedOption.getAttribute('data-lat');
        const lon = selectedOption.getAttribute('data-lon');
        
        // Update preview
        document.getElementById('techInitials').textContent = techName.split(' ').map(n => n[0]).join('').toUpperCase();
        document.getElementById('techName').textContent = techName;
        document.getElementById('techSpecialization').textContent = specialization;
        document.getElementById('techStatus').innerHTML = `
            <x-lucide-circle class="w-2 h-2 ${status === 'available' ? 'text-green-500 fill-green-500' : 'text-blue-500 fill-blue-500'}"/>
            ${status === 'available' ? 'Available' : 'On Duty'}
        `;
        document.getElementById('jobsCount').textContent = activeJobs;
        
        preview.classList.remove('hidden');
        preview.classList.add('animate-fadeIn');
        
        // Update modal preview
        document.getElementById('modalTechInitials').textContent = techName.split(' ').map(n => n[0]).join('').toUpperCase();
        document.getElementById('modalTechName').textContent = techName;
        document.getElementById('modalTechDetails').textContent = `${specialization} • ${activeJobs} active jobs`;
        
        // Show route on map
        hideNoSelection();
        if (lat && lon && lat !== 'null' && lon !== 'null') {
            drawRoute(parseFloat(lat), parseFloat(lon), techName, specialization);
        } else {
            document.getElementById('routeInfo').innerHTML = '<span class="text-yellow-600">Technician location not available</span>';
            hideMapLoading();
        }
    } else {
        // Reset state
        assignButton.disabled = true;
        preview.classList.add('hidden');
        showNoSelection();
        document.getElementById('routeInfo').textContent = 'Distance and time will appear here';
        
        // Remove route from map
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
        if (techMarker) {
            map.removeLayer(techMarker);
            techMarker = null;
        }
        
        // Reset map view
        map.setView([custLat, custLon], 13);
    }
});

// Modal confirm button
document.getElementById('modalConfirmBtn').addEventListener('click', function() {
    document.getElementById('assignForm').submit();
});

// Close modal on backdrop click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Initialize no selection state
showNoSelection();
</script>
@endsection