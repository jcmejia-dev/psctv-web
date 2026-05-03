@extends('layouts.admin')
@section('title', 'Edit Service Request')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                            <x-lucide-pencil class="w-6 h-6 text-white"/>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Edit Service Request</h2>
                            <p class="text-blue-100 mt-1">REQ-{{ $request->id }} • Update request details</p>
                        </div>
                    </div>
                    <a href="{{ route('service_requests.show', $request->id) }}" 
                       class="group flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm">
                        <x-lucide-arrow-left class="w-4 h-4 transition-transform group-hover:-translate-x-1"/>
                        <span>Back to Details</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <form method="POST" action="{{ route('service_requests.update', $request->id) }}" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Customer Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <x-lucide-user class="w-5 h-5 text-blue-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                    <x-lucide-user class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $request->customer_name) }}" 
                                       required class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                    <x-lucide-phone class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="tel" name="phone" value="{{ old('phone', $request->phone) }}" 
                                       class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-mail class="w-4 h-4 text-gray-400"/>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                                   class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-green-50">
                            <x-lucide-map-pin class="w-5 h-5 text-green-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Service Location</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Full Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-map-pin class="w-4 h-4 text-gray-400"/>
                            </div>
                            <input type="text" id="address" name="address" value="{{ old('address', $request->address) }}" 
                                   required class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        </div>
                        <p id="addressStatus" class="text-sm text-gray-500 mt-2 flex items-center gap-2 transition-all duration-300">
                            <x-lucide-info class="w-4 h-4"/>
                            <span>Edit the address to update the map preview</span>
                        </p>
                    </div>

                    <!-- Hidden inputs to store lat/lon -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $request->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $request->longitude) }}">

                    <!-- Map Preview -->
                    <div id="mapPreviewContainer" class="mt-4 rounded-2xl border border-gray-200 overflow-hidden shadow-lg transition-all duration-500">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <h4 class="font-medium text-gray-700 flex items-center gap-2">
                                <x-lucide-map class="w-4 h-4"/>
                                Location Preview
                            </h4>
                        </div>
                        <div id="mapPreview" class="w-full h-80"></div>
                    </div>
                </div>

                <!-- Service Details Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-purple-50">
                            <x-lucide-settings class="w-5 h-5 text-purple-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Service Details</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Service Type</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-wrench class="w-4 h-4 text-gray-400"/>
                            </div>
                            <select name="service_type" required 
                                    class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                <option value="Installation" {{ old('service_type', $request->service_type) == 'Installation' ? 'selected' : '' }}>Installation</option>
                                <option value="Repair" {{ old('service_type', $request->service_type) == 'Repair' ? 'selected' : '' }}>Repair</option>
                                <option value="Maintenance" {{ old('service_type', $request->service_type) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-lucide-chevron-down class="w-4 h-4 text-gray-400"/>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
                        <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-file-text class="w-4 h-4 text-gray-400"/>
                            </div>
                            <textarea name="notes" rows="4" 
                                      class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md resize-none"
                                      placeholder="Add any additional details...">{{ old('notes', $request->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500 flex items-center gap-2">
                            <x-lucide-shield-check class="w-4 h-4"/>
                            <span>All changes are securely saved</span>
                        </div>
                        <button type="submit" 
                                class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-4 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <x-lucide-save class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"/>
                            <span class="relative">Update Service Request</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slide-in {
    animation: slideInUp 0.6s ease-out;
}

/* Custom select styling */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    padding-right: 2.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize elements with slide-in animation
    const formElements = document.querySelectorAll('input, select, textarea');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 50);
    });

    const addressInput = document.getElementById('address');
    const statusText = document.getElementById('addressStatus');
    const mapContainer = document.getElementById('mapPreviewContainer');
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    let map, marker;

    // Load existing coordinates or default
    const initialLat = {{ $request->latitude ?? '0' }};
    const initialLon = {{ $request->longitude ?? '0' }};
    const hasCoords = {{ $request->latitude && $request->longitude ? 'true' : 'false' }};

    function showMap(lat, lon) {
        mapContainer.classList.add('slide-in');
        statusText.innerHTML = `<x-lucide-check class="w-4 h-4 text-green-500"/> Address verified. Map preview updated.`;
        statusText.classList.add('text-green-600');
        
        if (!map) {
            map = L.map('mapPreview').setView([lat, lon], hasCoords ? 15 : 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            
            marker = L.marker([lat, lon]).addTo(map)
                .bindPopup('Service Location')
                .openPopup();
                
            // Custom marker icon
            marker.setIcon(L.divIcon({
                className: 'custom-marker',
                html: `<div class="bg-blue-600 p-2 rounded-full border-2 border-white shadow-lg">
                         <x-lucide-map-pin class="w-4 h-4 text-white"/>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            }));
        } else {
            map.setView([lat, lon], 15);
            marker.setLatLng([lat, lon]);
        }
        
        latInput.value = lat;
        lonInput.value = lon;
    }

    // Initialize existing map position
    if (hasCoords) {
        showMap(initialLat, initialLon);
    }

    // Auto-update map when address changes
    let typingTimer;
    const delay = 1000;
    
    addressInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        statusText.innerHTML = `<x-lucide-loader class="w-4 h-4 animate-spin"/> Searching for address...`;
        statusText.classList.remove('text-green-600', 'text-red-600');
        statusText.classList.add('text-blue-600');
        
        typingTimer = setTimeout(async () => {
            const query = addressInput.value.trim();
            if (!query) {
                statusText.innerHTML = `<x-lucide-info class="w-4 h-4"/> Edit the address to update the map preview`;
                statusText.classList.remove('text-blue-600', 'text-green-600', 'text-red-600');
                statusText.classList.add('text-gray-500');
                return;
            }

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                const data = await response.json();
                
                if (data.length) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    showMap(lat, lon);
                } else {
                    statusText.innerHTML = `<x-lucide-alert-circle class="w-4 h-4"/> Address not found. Try again.`;
                    statusText.classList.remove('text-blue-600', 'text-green-600');
                    statusText.classList.add('text-red-600');
                }
            } catch (error) {
                console.error(error);
                statusText.innerHTML = `<x-lucide-wifi-off class="w-4 h-4"/> Error fetching address. Please try again.`;
                statusText.classList.remove('text-blue-600', 'text-green-600');
                statusText.classList.add('text-red-600');
            }
        }, delay);
    });

    // Add focus effects to form elements
    const formControls = document.querySelectorAll('input, select, textarea');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-xl');
        });
        
        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-xl');
        });
    });
});
</script>
@endsection