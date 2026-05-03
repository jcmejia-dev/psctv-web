    @extends('layouts.staff')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 space-y-6">

    <!-- Header & Status -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <a href="{{ route('staff.service_requests.index') }}" 
           class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
            ← Back to Requests
        </a>

        <span class="px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-2
            @if($serviceRequest->status=='pending') bg-yellow-100 text-yellow-700
            @elseif($serviceRequest->status=='assigned') bg-purple-100 text-purple-700
            @elseif($serviceRequest->status=='in-progress') bg-blue-100 text-blue-700
            @elseif($serviceRequest->status=='completed') bg-green-100 text-green-700
            @endif">
            <x-lucide-circle class="w-3 h-3"/>
            {{ ucfirst($serviceRequest->status) }}
        </span>
    </div>

    <!-- Overview + Timeline -->
    <div class="bg-white shadow-sm rounded-2xl border p-5 mt-4">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <!-- Request Info -->
            <div class="flex-1 text-gray-700 text-sm space-y-1">
                <h1 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                    <x-lucide-clipboard-list class="w-5 h-5 text-blue-600"/>
                    Service Request #REQ-{{ $serviceRequest->id }}
                </h1>
                <p class="flex items-center gap-1"><x-lucide-calendar class="w-4 h-4"/> Created: {{ $serviceRequest->created_at->format('M d, Y h:i A') }}</p>
                @if($serviceRequest->assigned_at)
                <p class="flex items-center gap-1"><x-lucide-user-check class="w-4 h-4"/> Assigned: {{ $serviceRequest->assigned_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->started_at)
                <p class="flex items-center gap-1"><x-lucide-play class="w-4 h-4"/> Started: {{ $serviceRequest->started_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->completed_at)
                <p class="flex items-center gap-1"><x-lucide-check class="w-4 h-4"/> Completed: {{ $serviceRequest->completed_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->creator)
                <p class="flex items-center gap-1"><x-lucide-user class="w-4 h-4"/> Created by: {{ $serviceRequest->creator->name }}</p>
                @endif
            </div>

            <!-- Redesigned Horizontal Timeline (Visual Progress Only - No Dates) -->
            @php
                $steps = [
                    ['label' => 'Created', 'icon' => 'calendar', 'done' => true, 'time' => $serviceRequest->created_at],
                    ['label' => 'Assigned', 'icon' => 'user-check', 'done' => in_array($serviceRequest->status, ['assigned', 'in-progress', 'completed']), 'time' => $serviceRequest->assigned_at],
                    ['label' => 'In Progress', 'icon' => 'play', 'done' => in_array($serviceRequest->status, ['in-progress', 'completed']), 'time' => $serviceRequest->started_at],
                    ['label' => 'Completed', 'icon' => 'check', 'done' => $serviceRequest->status === 'completed', 'time' => $serviceRequest->completed_at],
                ];
                $lastCompletedIndex = -1;
                for ($i = 0; $i < count($steps); $i++) {
                    if ($steps[$i]['done']) $lastCompletedIndex = $i;
                    else break;
                }
                $lineWidthPercent = $lastCompletedIndex > 0 ? round(($lastCompletedIndex / (count($steps) - 1)) * 100) : 0;
            @endphp

            <div class="flex-1 lg:max-w-md mt-3 lg:mt-0">
                <div class="relative">
                    <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
                    <div class="absolute top-6 left-0 h-1 bg-blue-600 rounded-full" style="width: {{ $lineWidthPercent }}%;"></div>
                    <div class="flex items-center justify-between relative z-10">
                        @foreach($steps as $index => $step)
                            <div class="flex flex-col items-center">
                                <div class="relative w-10 h-10 rounded-full flex items-center justify-center shadow-md flex-shrink-0 transition-colors duration-300"
                                     style="background-color: {{ $step['done'] ? '#EFF6FF' : '#F9FAFB' }}; border: 3px solid {{ $step['done'] ? '#3B82F6' : '#D1D5DB' }};"
                                     title="{{ $step['time'] ? $step['label'].': '.$step['time']->format('M d, Y h:i A') : $step['label'] }}">
                                    @if($step['done'])
                                        <x-lucide-{{ $step['icon'] }} class="w-5 h-5 text-blue-600" />
                                    @else
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    @endif
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="text-xs font-medium text-gray-700 block">{{ $step['label'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer & Technician -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        <!-- Customer -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-sm border space-y-4">
            <h3 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-user class="w-4 h-4 text-blue-500"/> Customer Details
            </h3>
            <div class="grid sm:grid-cols-2 gap-3 text-gray-700 text-xs sm:text-sm">
                <p class="flex items-center gap-2">
                    <x-lucide-user class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Name:</span> {{ $serviceRequest->customer_name ?? 'N/A' }}
                </p>
                <p class="flex items-center gap-2">
                    <x-lucide-phone class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Phone:</span> {{ $serviceRequest->phone ?? 'N/A' }}
                </p>
                <p class="sm:col-span-2 flex items-start gap-2">
                    <x-lucide-map-pin class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"/>
                    <span class="font-medium">Address:</span> {{ $serviceRequest->address ?? 'N/A' }}
                </p>
                <p class="flex items-center gap-2">
                    <x-lucide-wrench class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Service:</span> {{ $serviceRequest->service_type ?? 'N/A' }}
                </p>
                <p class="sm:col-span-2 flex items-start gap-2">
                    <x-lucide-file-text class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"/>
                    <span class="font-medium">Notes:</span> 
                    @if($serviceRequest->notes) {{ $serviceRequest->notes }} @else <span class="text-gray-400 italic">None</span> @endif
                </p>
            </div>
        </div>

        <!-- Technician -->
        <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-6 rounded-2xl shadow-lg border border-indigo-100 space-y-5 h-full relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-200/30 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/30 to-transparent rounded-full translate-y-12 -translate-x-12"></div>

            <h3 class="text-base sm:text-lg font-bold text-indigo-800 flex items-center gap-3 relative z-10">
                <div class="p-2 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-md">
                    <x-lucide-user-check class="w-5 h-5 text-white"/>
                </div>
                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Technician Profile</span>
            </h3>

            @if($serviceRequest->technician)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-md border border-white/50 relative z-10">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 relative">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 p-1 shadow-lg">
                                <img src="{{ $serviceRequest->technician->profile_picture ?? 'https://via.placeholder.com/64?text=👤' }}"
                                     alt="{{ $serviceRequest->technician->full_name ?? 'Technician' }}"
                                     class="w-full h-full rounded-full object-cover bg-white"
                                     onerror="this.src='https://via.placeholder.com/64?text=👤'">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white shadow-md flex items-center justify-center">
                                <x-lucide-check class="w-3 h-3 text-white"/>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 space-y-2">
                            <div>
                                <p class="text-lg font-bold text-gray-900 truncate">{{ $serviceRequest->technician->full_name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-sm">
                                        <x-lucide-wrench class="w-3 h-3 mr-1"/>
                                        {{ $serviceRequest->technician->specialization ?? 'General Technician' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1 text-sm">
                                    <x-lucide-briefcase class="w-4 h-4 text-orange-500"/>
                                    <span class="font-medium text-gray-700">Active Jobs:</span>
                                    <span class="px-2 py-1 bg-gradient-to-r from-orange-400 to-red-400 text-white rounded-full text-xs font-bold shadow-sm">
                                        {{ $serviceRequest->technician->jobs_pending ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <div class="flex gap-2 relative z-10">
                        <a href="{{ route('staff.service_requests.assignPage', $serviceRequest->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <x-lucide-refresh-cw class="w-4 h-4"/>
                            Re-assign Technician
                        </a>
                        <a href="{{ route('staff.technicians.show', $serviceRequest->technician->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-3 text-sm font-semibold bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <x-lucide-eye class="w-4 h-4"/>
                            View Profile
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8 relative z-10">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center shadow-lg">
                        <x-lucide-user-x class="w-10 h-10 text-gray-500"/>
                    </div>
                    <p class="text-gray-600 font-medium text-base mb-2">No Technician Assigned</p>
                    <p class="text-gray-500 text-sm">Assign a skilled technician to handle this service request</p>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <a href="{{ route('staff.service_requests.assignPage', $serviceRequest->id) }}"
                       class="w-full inline-flex items-center justify-center gap-3 py-4 px-6 text-base font-bold bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 relative z-10">
                        <x-lucide-user-plus class="w-5 h-5"/>
                        Assign Technician Now
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white rounded-2xl shadow-sm border mb-6 overflow-hidden">
        <h2 class="p-3 font-semibold text-gray-800 flex items-center gap-2 border-b text-sm sm:text-base">
            <x-lucide-map class="w-4 h-4 text-red-600"/> Technician → Customer Route
        </h2>
        <div class="relative p-4">
            <div id="map" class="w-full h-64 sm:h-80 md:h-96" aria-label="Route from technician to customer location"></div>
            <div id="mapLoading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 15px; border-radius: 5px; z-index: 1001; font-size: 16px; color: blue;">Loading route...</div>
        </div>
        <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
            <div id="routeInfo" class="font-semibold text-sm mb-2" style="color: green;">Route Info: Not loaded yet</div>
            <button id="refreshRoute" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transition">Refresh Route</button>
            <button id="fitRouteButton" class="ml-2 px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition">Fit to Route</button>
        </div>
    </div>

    <!-- Completion Details -->
    @if($serviceRequest->status === 'completed')
        <div class="bg-white rounded-2xl shadow-sm border p-5 space-y-4 mb-4">
            <h2 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-check-circle class="w-4 h-4 text-green-600"/> Completion Details
            </h2>
            <div>
                <p class="text-gray-700 text-xs sm:text-sm font-medium flex items-center gap-1 mb-2">
                    <x-lucide-file-text class="w-4 h-4 text-gray-400"/> Report:
                </p>
                @if($serviceRequest->report)
                    <p class="text-gray-600 text-xs sm:text-sm mt-1 whitespace-pre-line bg-gray-50 p-3 rounded-lg border">{{ $serviceRequest->report }}</p>
                @else
                    <div class="flex items-center gap-2 text-gray-500 italic text-sm py-3">
                        <x-lucide-file class="w-4 h-4"/> No report submitted.
                    </div>
                @endif
            </div>
            <div>
                <p class="text-gray-700 text-xs sm:text-sm font-medium flex items-center gap-1 mb-2">
                    <x-lucide-image class="w-4 h-4 text-gray-400"/> Proof Photos:
                </p>
                @if($serviceRequest->photos && $serviceRequest->photos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($serviceRequest->photos as $photo)
                            <div class="relative group cursor-pointer">
                                <img src="{{ $photo->url }}"
                                     alt="Proof photo {{ $loop->iteration }}"
                                     class="w-full h-28 sm:h-32 aspect-square object-cover rounded-lg border shadow-sm transition-transform group-hover:scale-105"
                                     loading="lazy"
                                     onclick="openPhotoModal('{{ $photo->url }}')">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-lucide-zoom-in class="w-5 h-5 text-white"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-500 italic text-sm py-3">
                        <x-lucide-image-off class="w-4 h-4"/> No proof photos uploaded.
                    </div>
                @endif
            </div>
            <!-- Rating Section -->
            <div>
                <p class="text-gray-700 text-xs sm:text-sm font-medium flex items-center gap-1 mb-2">
                    <x-lucide-star class="w-4 h-4 text-yellow-500"/> Rating:
                </p>
                @if($serviceRequest->rating)
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <x-lucide-star class="w-5 h-5 {{ $i <= $serviceRequest->rating ? 'text-yellow-500 fill-current' : 'text-gray-300' }}"/>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">({{ $serviceRequest->rating }}/5)</span>
                        @if($serviceRequest->rated_at)
                            <span class="text-xs text-gray-500">Rated on {{ $serviceRequest->rated_at->format('M d, Y h:i A') }}</span>
                        @endif
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-500 italic text-sm py-3">
                        <x-lucide-star class="w-4 h-4"/> Not rated yet.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Update Status Form -->
    @if($serviceRequest->status !== 'completed')
        <div class="bg-white rounded-2xl shadow-sm border p-5 mt-3">
            <h2 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <x-lucide-activity class="w-4 h-4 text-blue-600"/> Update Status
            </h2>

            <form action="{{ route('staff.service_requests.updateStatus', $serviceRequest->id) }}" 
                method="POST" enctype="multipart/form-data" class="space-y-4" id="statusForm">
                @csrf
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $serviceRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ old('status', $serviceRequest->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in-progress" {{ old('status', $serviceRequest->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $serviceRequest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="completionFields" class="space-y-3 pt-3 border-t border-gray-100" style="display:none;">
                    <div>
                        <label for="report" class="block text-xs font-medium text-gray-700 mb-1">Completion Report <span class="text-red-500">*</span></label>
                        <textarea name="report" id="report" rows="4" placeholder="Describe the work completed, issues resolved, etc..." 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('report') border-red-500 @enderror resize-vertical"
                                required>{{ old('report', $serviceRequest->report) }}</textarea>
                        @error('report')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="photos" class="block text-xs font-medium text-gray-700 mb-1">Proof Photos (Optional, multiple allowed)</label>
                        <input type="file" name="photos[]" id="photos" multiple accept="image/*" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photos') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('photos')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Upload images to verify completion (JPEG, PNG up to 5MB each).</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <x-lucide-save class="w-4 h-4"/> Save Changes
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>

<!-- Existing Leaflet and Routing Machine links -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<!-- Pusher and Echo scripts -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script> <!-- Ensure Echo is configured -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded and initializing map...');

    const status = document.getElementById('status');
    const completionFields = document.getElementById('completionFields');
    if (status) {
        const toggle = () => completionFields.style.display = status.value === 'completed' ? 'block' : 'none';
        toggle();
        status.addEventListener('change', toggle);
    }

    window.openPhotoModal = (src) => window.open(src, '_blank', 'noopener,noreferrer');

    const custLat = parseFloat(@json($serviceRequest->latitude)) || 12.8797;
    const custLon = parseFloat(@json($serviceRequest->longitude)) || 121.7740;
    let techLat = parseFloat(@json(optional($serviceRequest->technician)->latitude)) || null;
    let techLon = parseFloat(@json(optional($serviceRequest->technician)->longitude)) || null;

    const customerName = @json(optional($serviceRequest->customer)->user->name ?? 'Customer');
    const technicianName = @json(optional($serviceRequest->technician)->user->name ?? 'Technician');
    const customerAddress = @json($serviceRequest->address ?? 'Unknown Location');
    const technicianAddress = @json(optional($serviceRequest->technician)->address ?? 'Technician Location');
    const technicianId = @json(optional($serviceRequest->technician)->id);

    if (isNaN(custLat) || isNaN(custLon)) {
        console.log('Using default customer coordinates: [12.8797, 121.7740]');
        custLat = 12.8797;
        custLon = 121.7740;
    }
    if (techLat && techLon && (isNaN(techLat) || isNaN(techLon))) {
        console.log('Invalid technician coordinates; skipping.');
        techLat = null;
        techLon = null;
    }

    let centerLat = custLat;
    let centerLon = custLon;
    let initialZoom = 13;

    if (techLat && techLon) {
        centerLat = (custLat + techLat) / 2;
        centerLon = (custLon + techLon) / 2;
        initialZoom = Math.sqrt(Math.pow(custLat - techLat, 2) + Math.pow(custLon - techLon, 2)) * 100 > 50 ? 10 : 12;
        console.log(`Centering map on midpoint: [${centerLat}, ${centerLon}] with zoom ${initialZoom}`);
    }

    const map = L.map('map', { zoomControl: false }).setView([centerLat, centerLon], initialZoom);
    L.control.zoom({ position: 'topright' }).addTo(map);
    L.control.scale().addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const customerIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png', iconSize: [45, 45], iconAnchor: [22, 45], popupAnchor: [0, -40] });
    const technicianIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/1995/1995547.png', iconSize: [45, 45], iconAnchor: [22, 45], popupAnchor: [0, -40] });

    const customerMarker = L.marker([custLat, custLon], { icon: customerIcon, zIndexOffset: 1000 }).bindPopup(`<b>Customer Location</b><br>${customerName}<br>${customerAddress}`).addTo(map);
    let technicianMarker = null;

    function addOrUpdateTechnicianMarker(lat, lon) {
        if (technicianMarker) {
            technicianMarker.setLatLng([lat, lon]);
            map.panTo([lat, lon], { animate: true });
            console.log('Technician marker updated to: [' + lat + ', ' + lon + ']');
        } else {
            technicianMarker = L.marker([lat, lon], { icon: technicianIcon, zIndexOffset: 1000 }).bindPopup(`<b>Technician Location</b><br>${technicianName}<br>${technicianAddress}`).addTo(map);
        }
    }

    if (techLat && techLon) {
        addOrUpdateTechnicianMarker(techLat, techLon);
    }

    let route = null;
    let routeTimeout = null;

    function drawRoute() {
        // Clear any existing timeout
        if (routeTimeout) clearTimeout(routeTimeout);

        document.getElementById('mapLoading').style.display = 'block';
        document.getElementById('routeInfo').innerHTML = 'Route Info: Loading...';
        console.log('Attempting to draw route...');

        // Safety timeout to prevent infinite loading
        routeTimeout = setTimeout(() => {
            document.getElementById('mapLoading').style.display = 'none';
            document.getElementById('routeInfo').innerHTML = 'Route Info: Loading timed out. Please refresh.';
            console.log('Route loading timed out after 15 seconds');
        }, 15000);

        if (techLat && techLon) {
            if (route) map.removeControl(route);

            route = L.Routing.control({
                waypoints: [L.latLng(techLat, techLon), L.latLng(custLat, custLon)],
                draggableWaypoints: false,
                addWaypoints: false,
                routeWhileDragging: false,
                fitSelectedRoutes: true,
                showAlternatives: false,
                lineOptions: { styles: [{ color: 'blue', opacity: 0.8, weight: 6 }] },
                createMarker: () => null,
                router: L.Routing.osrmv1({ serviceUrl: 'https://router.project-osrm.org/route/v1' }),
                instructions: false // Disable instructions completely
            }).addTo(map);

            route.on('routesfound', (e) => {
                clearTimeout(routeTimeout);
                try {
                    if (e.routes && e.routes.length > 0) {
                        const summary = e.routes[0].summary;
                        const distance = (summary.totalDistance / 1000).toFixed(1);
                        const duration = (summary.totalDuration / 60).toFixed(0);
                        document.getElementById('routeInfo').innerHTML = `Route Info: Distance: ${distance} km | Time: ${duration} min`;
                    } else {
                        document.getElementById('routeInfo').innerHTML = 'Route Info: No route data found.';
                    }
                } catch (error) {
                    document.getElementById('routeInfo').innerHTML = 'Route Info: Error calculating details.';
                    console.error('Error in routesfound:', error);
                }
                document.getElementById('mapLoading').style.display = 'none';
                console.log('Route successfully loaded.');
            });

            route.on('routingerror', (e) => {
                clearTimeout(routeTimeout);
                console.error('Routing error:', e.error);
                document.getElementById('routeInfo').innerHTML = 'Route Info: Error loading route.';
                document.getElementById('mapLoading').style.display = 'none';
                const polyline = L.polyline([[techLat, techLon], [custLat, custLon]], { color: 'red', dashArray: '5, 10' }).addTo(map);
                map.fitBounds(polyline.getBounds());
            });
        } else {
            document.getElementById('routeInfo').innerHTML = 'Route Info: Technician location unavailable.';
            document.getElementById('mapLoading').style.display = 'none';
        }
    }

    drawRoute();

    document.getElementById('refreshRoute').addEventListener('click', () => {
        console.log('Refreshing route...');
        drawRoute();
    });

    document.getElementById('fitRouteButton').addEventListener('click', () => {
        console.log('Fitting route to view...');
        if (route && route.getBounds) {
            map.fitBounds(route.getBounds());
        } else if (techLat && techLon) {
            map.fitBounds([[custLat, custLon], [techLat, techLon]]);
        } else {
            console.error('No route or markers to fit.');
            alert('No route available to fit.');
        }
    });

    if (technicianId) {
        Echo.channel('technician-location.' + technicianId)
            .listen('TechnicianLocationUpdated', (e) => {
                if (e.latitude && e.longitude) {
                    techLat = e.latitude;
                    techLon = e.longitude;
                    addOrUpdateTechnicianMarker(techLat, techLon);
                    drawRoute();
                } else {
                    console.error('Invalid location data from event.');
                }
            })
            .error((error) => console.error('Pusher error:', error));
    }
});
</script>

@endsection