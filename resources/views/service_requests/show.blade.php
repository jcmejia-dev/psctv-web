@extends('layouts.admin')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 space-y-6">

    <!-- Header & Status -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <a href="{{ route('service_requests.index') }}" 
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
<div class="flex-1 lg:max-w-md mt-3 lg:mt-0">
    @php
        $steps = [
            [
                'label' => 'Created',
                'icon' => 'calendar',
                'done' => true,
                'time' => $serviceRequest->created_at,
            ],
            [
                'label' => 'Assigned',
                'icon' => 'user-check',
                'done' => in_array($serviceRequest->status, ['assigned', 'in-progress', 'completed']),
                'time' => $serviceRequest->assigned_at,
            ],
            [
                'label' => 'In Progress',
                'icon' => 'play',
                'done' => in_array($serviceRequest->status, ['in-progress', 'completed']),
                'time' => $serviceRequest->started_at,
            ],
            [
                'label' => 'Completed',
                'icon' => 'check',
                'done' => $serviceRequest->status === 'completed',
                'time' => $serviceRequest->completed_at,
            ],
        ];

        $lastCompletedIndex = -1;
        for ($i = 0; $i < count($steps); $i++) {
            if ($steps[$i]['done']) {
                $lastCompletedIndex = $i;
            } else {
                break;
            }
        }

        $lineWidthPercent = $lastCompletedIndex > 0 ? round(($lastCompletedIndex / (count($steps) - 1)) * 100) : 0;
    @endphp

    <div class="relative">
        <!-- Background Progress Line -->
        <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>

        <!-- Active Progress Line -->
        @if($lineWidthPercent > 0)
            <div class="absolute top-6 left-0 h-1 bg-blue-600 rounded-full" style="width: {{ $lineWidthPercent }}%;"></div>
        @endif

        <div class="flex items-center justify-between relative z-10">
            @foreach($steps as $index => $step)
                <div class="flex flex-col items-center">
                    <!-- Step Marker -->
                    <div class="relative w-10 h-10 rounded-full flex items-center justify-center shadow-md flex-shrink-0 transition-colors duration-300"
                        style="background-color: {{ $step['done'] ? '#EFF6FF' : '#F9FAFB' }}; border: 3px solid {{ $step['done'] ? '#3B82F6' : '#D1D5DB' }};"
                        title="{{ $step['time'] ? $step['label'].': '.$step['time']->format('M d, Y h:i A') : $step['label'] }}">
                        @if($step['done'])
                            <x-lucide-{{ $step['icon'] }} class="w-5 h-5 text-blue-600" />
                        @else
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        @endif
                    </div>

                    <!-- Step Label -->
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
                    @if($serviceRequest->notes)
                        {{ $serviceRequest->notes }}
                    @else
                        <span class="text-gray-400 italic">None</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Technician -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border space-y-4 h-full">
            <h3 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-user-check class="w-4 h-4 text-purple-600"/> Technician
            </h3>
            @if($serviceRequest->technician)
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <img src="{{ $serviceRequest->technician->profile_picture_url ?? 'https://via.placeholder.com/48?text=👤' }}" 
                            alt="{{ $serviceRequest->technician->full_name ?? 'Technician' }}" 
                            class="w-12 h-12 rounded-full border-2 border-gray-200 shadow-sm object-cover bg-gray-100"
                            onerror="this.src='https://via.placeholder.com/48?text=👤'">
                    </div>
                    <div class="flex-1 min-w-0 text-xs sm:text-sm">
                        <p class="font-semibold text-gray-900 truncate">{{ $serviceRequest->technician->full_name }}</p>
                        <p class="text-gray-600">{{ $serviceRequest->technician->specialization ?? 'General Technician' }}</p>
                        <p class="text-gray-500">Active Jobs: {{ $serviceRequest->technician->jobs_pending ?? 0 }}</p>
                    </div>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <a href="{{ route('service_requests.assignPage', $serviceRequest->id) }}"
                    class="inline-flex items-center gap-1 mt-3 px-3 py-2 text-xs bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-refresh-cw class="w-3 h-3"/> Re-assign
                    </a>
                @endif
            @else
                <div class="text-center py-6">
                    <x-lucide-user-x class="w-8 h-8 text-gray-400 mx-auto mb-2"/>
                    <p class="text-gray-500 italic text-sm">No technician assigned yet.</p>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <a href="{{ route('service_requests.assignPage', $serviceRequest->id) }}"
                    class="w-full flex items-center justify-center gap-1 py-2 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-user-plus class="w-3 h-3"/> Assign Technician
                    </a>
                @endif
            @endif
        </div>
    </div>

<!-- Route & Map Section - Only show when technician is assigned -->
@if($serviceRequest->technician)
<div class="bg-white rounded-2xl shadow-sm border mb-6 overflow-hidden">
    <h2 class="p-3 font-semibold text-gray-800 flex items-center gap-2 border-b text-sm sm:text-base">
        <x-lucide-map class="w-4 h-4 text-red-600"/> Route & Directions
    </h2>
    
    <!-- Route Information -->
    <div class="p-4 border-b bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-3 bg-white rounded-lg border">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <x-lucide-map-pin class="w-4 h-4 text-blue-600"/>
                    <span class="text-sm font-medium text-gray-700">Distance</span>
                </div>
                <div id="routeDistance" class="text-lg font-bold text-blue-600">Calculating...</div>
            </div>
            <div class="text-center p-3 bg-white rounded-lg border">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <x-lucide-clock class="w-4 h-4 text-green-600"/>
                    <span class="text-sm font-medium text-gray-700">Estimated Time</span>
                </div>
                <div id="routeDuration" class="text-lg font-bold text-green-600">Calculating...</div>
            </div>
            <div class="text-center p-3 bg-white rounded-lg border">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <x-lucide-navigation class="w-4 h-4 text-purple-600"/>
                    <span class="text-sm font-medium text-gray-700">Route Type</span>
                </div>
                <div id="routeType" class="text-lg font-bold text-purple-600">Driving</div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative p-4">
        <div id="map" class="w-full h-64 sm:h-80 md:h-96" aria-label="Route map"></div>
        <div id="mapLoading" class="absolute inset-0 bg-white/95 flex items-center justify-center z-1001">
            <div class="text-center">
                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                <p class="text-blue-600 font-medium">Loading route map...</p>
            </div>
        </div>
        <div id="mapError" class="absolute inset-0 bg-white/95 flex items-center justify-center z-1001 hidden">
            <div class="text-center p-6">
                <x-lucide-alert-triangle class="w-12 h-12 text-red-500 mx-auto mb-3"/>
                <p class="text-red-600 font-medium mb-2">Unable to load route map</p>
                <p class="text-gray-600 text-sm mb-4" id="errorMessage"></p>
                <button id="retryMap" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>

    <!-- Directions Panel -->
    <div class="border-t">
        <div class="p-4 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <x-lucide-list-ordered class="w-4 h-4 text-blue-600"/> Turn-by-Turn Directions
            </h3>
            <div id="directionsPanel" class="bg-white rounded-lg border max-h-60 overflow-y-auto p-3">
                <div class="text-center text-gray-500 py-4">
                    <x-lucide-navigation class="w-8 h-8 mx-auto mb-2 text-gray-400"/>
                    <p>Directions will appear here once route is calculated</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Details -->
    <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="font-semibold text-green-600 text-sm mb-2">
                    Route Information
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div>From: <span id="technicianLocation">{{ $serviceRequest->technician->address ?? 'Current Location' }}</span></div>
                    <div>To: <span id="customerAddress">{{ $serviceRequest->address ?? 'N/A' }}</span></div>
                    <div>Customer Coordinates: <span id="displayCoordinates">
                        @if($serviceRequest->latitude && $serviceRequest->longitude)
                            {{ number_format($serviceRequest->latitude, 6) }}, {{ number_format($serviceRequest->longitude, 6) }}
                        @else
                            Not available
                        @endif
                    </span></div>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="refreshMap" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transition">Refresh Route</button>
                <button id="fitToRoute" class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition">Fit to Route</button>
            </div>
        </div>
    </div>
</div>
@else
<!-- Show message when no technician is assigned -->
<div class="bg-yellow-50 rounded-2xl shadow-sm border border-yellow-200 mb-6 overflow-hidden">
    <div class="p-6 text-center">
        <x-lucide-map-pin class="w-12 h-12 text-yellow-500 mx-auto mb-3"/>
        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Route Unavailable</h3>
        <p class="text-yellow-700 mb-4">Route planning will be available once a technician is assigned to this service request.</p>
        <a href="{{ route('service_requests.assignPage', $serviceRequest->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow-sm transition-colors duration-200">
            <x-lucide-user-plus class="w-4 h-4"/> Assign Technician
        </a>
    </div>
</div>
@endif

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
    </div>
@endif

    <!-- Update Status Form -->
    @if($serviceRequest->status !== 'completed')
        <div class="bg-white rounded-2xl shadow-sm border p-5 mt-3">
            <h2 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <x-lucide-activity class="w-4 h-4 text-blue-600"/> Update Status
            </h2>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('service_requests.updateStatus', $serviceRequest->id) }}" 
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

                <div id="completionFields" class="space-y-3 pt-3 border-t border-gray-100" style="display: {{ old('status', $serviceRequest->status) == 'completed' ? 'block' : 'none' }};">
                    <div>
                        <label for="report" class="block text-xs font-medium text-gray-700 mb-1">Completion Report <span class="text-red-500">*</span></label>
                        <textarea name="report" id="report" rows="4" placeholder="Describe the work completed, issues resolved, etc..." 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('report') border-red-500 @enderror resize-vertical">{{ old('report', $serviceRequest->report) }}</textarea>
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
                        <x-lucide-save class="w-4 h-4"/> Update Status
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full w-full">
        <button id="closeModal" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-2">
            <x-lucide-x class="w-6 h-6" />
        </button>
        <button id="prevPhoto" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3">
            <x-lucide-chevron-left class="w-6 h-6" />
        </button>
        <button id="nextPhoto" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 text-white hover:text-gray-300 transition-colors bg-black/50 rounded-full p-3">
            <x-lucide-chevron-right class="w-6 h-6" />
        </button>
        <img id="modalImage" src="" alt="Full size proof photo" class="w-full h-auto max-h-[90vh] object-contain rounded-lg">
    </div>
</div>

<!-- Leaflet Maps & Routing -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Service Request Details page loaded');

    // Photo Modal Functionality
    let currentPhotoIndex = 0;
    const photos = @json($serviceRequest->photos ?? []);
    
    function openPhotoModal(photoUrl, index = 0) {
        currentPhotoIndex = index;
        const modal = document.getElementById('photoModal');
        const modalImage = document.getElementById('modalImage');
        
        modalImage.src = photoUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        updateNavigationButtons();
    }

    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function showNextPhoto() {
        if (currentPhotoIndex < photos.length - 1) {
            currentPhotoIndex++;
            const photoUrl = photos[currentPhotoIndex].url;
            document.getElementById('modalImage').src = photoUrl;
            updateNavigationButtons();
        }
    }

    function showPrevPhoto() {
        if (currentPhotoIndex > 0) {
            currentPhotoIndex--;
            const photoUrl = photos[currentPhotoIndex].url;
            document.getElementById('modalImage').src = photoUrl;
            updateNavigationButtons();
        }
    }

    function updateNavigationButtons() {
        const prevButton = document.getElementById('prevPhoto');
        const nextButton = document.getElementById('nextPhoto');
        
        prevButton.style.display = currentPhotoIndex > 0 ? 'block' : 'none';
        nextButton.style.display = currentPhotoIndex < photos.length - 1 ? 'block' : 'none';
    }

    // Event listeners for photo modal
    document.getElementById('closeModal')?.addEventListener('click', closePhotoModal);
    document.getElementById('prevPhoto')?.addEventListener('click', showPrevPhoto);
    document.getElementById('nextPhoto')?.addEventListener('click', showNextPhoto);
    
    // Close modal when clicking on background
    document.getElementById('photoModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'photoModal') {
            closePhotoModal();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('photoModal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closePhotoModal();
            if (e.key === 'ArrowLeft') showPrevPhoto();
            if (e.key === 'ArrowRight') showNextPhoto();
        }
    });

    // Update image click handlers to use modal
    document.querySelectorAll('[onclick*="openPhotoModal"]').forEach((img, index) => {
        img.addEventListener('click', () => {
            const photoUrl = img.src;
            openPhotoModal(photoUrl, index);
        });
    });

    // Status form functionality
    const status = document.getElementById('status');
    const completionFields = document.getElementById('completionFields');
    const reportTextarea = document.getElementById('report');
    const statusForm = document.getElementById('statusForm');

    function toggleCompletionFields() {
        const isCompleted = status.value === 'completed';
        completionFields.style.display = isCompleted ? 'block' : 'none';
        
        // Toggle required attribute on report field
        if (reportTextarea) {
            if (isCompleted) {
                reportTextarea.setAttribute('required', 'required');
            } else {
                reportTextarea.removeAttribute('required');
            }
        }
        
        console.log('Status changed to:', status.value, 'Completion fields:', isCompleted ? 'visible' : 'hidden');
    }

    // Initialize on page load
    if (status && completionFields) {
        toggleCompletionFields();
        status.addEventListener('change', toggleCompletionFields);
    }

    // Form submission handling
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            const isCompleted = status.value === 'completed';
            const reportValue = reportTextarea ? reportTextarea.value.trim() : '';
            
            if (isCompleted && (!reportValue || reportValue === '')) {
                e.preventDefault();
                alert('Please provide a completion report when marking the service as completed.');
                reportTextarea.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div> Updating...';
            }
            
            console.log('Submitting status form with status:', status.value);
        });
    }

    // Only initialize map if technician is assigned and has coordinates
    @if($serviceRequest->technician && $serviceRequest->technician->latitude && $serviceRequest->technician->longitude)
    initializeRouteMap();
    @elseif($serviceRequest->technician)
    console.log('Technician assigned but missing coordinates. Map not initialized.');
    @endif

    function initializeRouteMap() {
        console.log('Initializing route map...');

        // Customer coordinates from service request
        let custLat = parseFloat(@json($serviceRequest->latitude));
        let custLon = parseFloat(@json($serviceRequest->longitude));

        // Technician coordinates from technician model - FIXED with null checks
        let techLat = parseFloat(@json($serviceRequest->technician->latitude ?? null));
        let techLon = parseFloat(@json($serviceRequest->technician->longitude ?? null));

        const customerName = @json(optional($serviceRequest->customer)->user->name ?? 'Customer');
        const customerAddress = @json($serviceRequest->address ?? 'Unknown Location');
        const technicianName = @json($serviceRequest->technician->full_name ?? 'Technician');
        const technicianAddress = @json($serviceRequest->technician->address ?? 'Technician Location');
        const technicianPhoto = @json($serviceRequest->technician->profile_picture_url ?? 'https://via.placeholder.com/48?text=👤');
        const technicianSpecialization = @json($serviceRequest->technician->specialization ?? 'General Technician');

        // Validate customer coordinates - use default if invalid
        if (isNaN(custLat) || isNaN(custLon)) {
            console.log('Invalid customer coordinates, using default location');
            custLat = 12.8797; // Default Philippines coordinates
            custLon = 121.7740;
        }

        // Validate technician coordinates - use default if invalid
        if (isNaN(techLat) || isNaN(techLon)) {
            console.log('Invalid technician coordinates, using default location');
            techLat = 12.9297; // Default Philippines coordinates (slightly different from customer)
            techLon = 121.8240;
            
            // Update display to show coordinates are estimated
            document.getElementById('technicianLocation').textContent = 'Default Location (Coordinates missing)';
        } else {
            // Show actual technician address if coordinates are valid
            document.getElementById('technicianLocation').textContent = technicianAddress;
        }

        let map = null;
        let routeControl = null;

        function initMap() {
            try {
                // Hide loading and error
                document.getElementById('mapLoading').style.display = 'none';
                document.getElementById('mapError').style.display = 'none';

                // Create map centered between technician and customer
                const centerLat = (techLat + custLat) / 2;
                const centerLon = (techLon + custLon) / 2;
                
                map = L.map('map', { 
                    zoomControl: true,
                    zoomSnap: 0.5,
                    zoomDelta: 0.5
                }).setView([centerLat, centerLon], 12);
                
                L.control.zoom({ 
                    position: 'topright'
                }).addTo(map);
                
                L.control.scale({
                    imperial: false,
                    metric: true
                }).addTo(map);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Create custom icons
                const technicianIcon = L.divIcon({
                    className: 'technician-marker',
                    html: `
                        <div style="background: #3b82f6; padding: 10px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    `,
                    iconSize: [44, 44],
                    iconAnchor: [22, 44]
                });

                const customerIcon = L.divIcon({
                    className: 'customer-marker',
                    html: `
                        <div style="background: #ef4444; padding: 10px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/>
                            </svg>
                        </div>
                    `,
                    iconSize: [44, 44],
                    iconAnchor: [22, 44]
                });

                // Add markers with actual addresses and technician photo
                const technicianMarker = L.marker([techLat, techLon], { icon: technicianIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-3 max-w-xs">
                            <div class="flex items-center gap-3 mb-3">
                                <img src="${technicianPhoto}" 
                                    alt="${technicianName}" 
                                    class="w-12 h-12 rounded-full border-2 border-blue-200 shadow-sm object-cover bg-gray-100"
                                    onerror="this.src='https://via.placeholder.com/48?text=👤'">
                                <div>
                                    <b class="text-sm text-blue-600">Technician Location</b><br>
                                    <span class="text-xs font-semibold">${technicianName}</span><br>
                                    <span class="text-xs text-gray-500">${technicianSpecialization}</span>
                                </div>
                            </div>
                            <div class="text-xs space-y-1">
                                <div><span class="font-medium">Address:</span> ${technicianAddress}</div>
                                <div><span class="font-medium">Coordinates:</span> ${techLat.toFixed(6)}, ${techLon.toFixed(6)}</div>
                            </div>
                        </div>
                    `);

                const customerMarker = L.marker([custLat, custLon], { icon: customerIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-3 max-w-xs">
                            <b class="text-sm text-red-600">Customer Location</b><br>
                            <span class="text-xs font-semibold">${customerName}</span><br>
                            <div class="text-xs space-y-1 mt-2">
                                <div><span class="font-medium">Address:</span> ${customerAddress}</div>
                                <div><span class="font-medium">Coordinates:</span> ${custLat.toFixed(6)}, ${custLon.toFixed(6)}</div>
                                <div><span class="font-medium">Service:</span> {{ $serviceRequest->service_type ?? 'N/A' }}</div>
                            </div>
                        </div>
                    `)
                    .openPopup();

                // Initialize routing control with actual coordinates
                routeControl = L.Routing.control({
                    waypoints: [
                        L.latLng(techLat, techLon), // Actual technician location
                        L.latLng(custLat, custLon)  // Actual customer location
                    ],
                    routeWhileDragging: false,
                    showAlternatives: false,
                    fitSelectedRoutes: true,
                    show: false, // Hide default instructions panel
                    lineOptions: {
                        styles: [
                            {
                                color: '#3b82f6',
                                opacity: 0.8,
                                weight: 6
                            }
                        ]
                    },
                    createMarker: function() { return null; } // Don't create default markers
                }).addTo(map);

                // Listen for route events
                routeControl.on('routesfound', function(e) {
                    const routes = e.routes;
                    const summary = routes[0].summary;
                    
                    // Update route information
                    document.getElementById('routeDistance').textContent = 
                        (summary.totalDistance / 1000).toFixed(1) + ' km';
                    
                    const durationMinutes = Math.round(summary.totalTime / 60);
                    document.getElementById('routeDuration').textContent = 
                        durationMinutes + ' min';
                    
                    // Display turn-by-turn directions
                    displayDirections(routes[0].instructions);
                    
                    console.log('✅ Route calculated successfully using actual coordinates');
                    console.log('Technician:', techLat, techLon);
                    console.log('Customer:', custLat, custLon);
                    console.log('Distance:', (summary.totalDistance / 1000).toFixed(1) + ' km');
                    console.log('Time:', durationMinutes + ' min');
                });

                routeControl.on('routingerror', function(e) {
                    console.error('Route calculation error:', e.error);
                    document.getElementById('routeDistance').textContent = 'Error';
                    document.getElementById('routeDuration').textContent = 'Error';
                    document.getElementById('directionsPanel').innerHTML = `
                        <div class="text-center text-red-500 py-4">
                            <x-lucide-alert-triangle class="w-8 h-8 mx-auto mb-2"/>
                            <p>Unable to calculate route</p>
                            <p class="text-xs mt-2">Check if coordinates are valid</p>
                        </div>
                    `;
                });

                console.log('✅ Route map initialized with actual technician location');
                
                // Update location info
                document.getElementById('displayCoordinates').textContent = 
                    `${custLat.toFixed(6)}, ${custLon.toFixed(6)}`;

            } catch (error) {
                console.error('Map initialization error:', error);
                showMapError('Failed to initialize route map: ' + error.message);
            }
        }

        function displayDirections(instructions) {
            const directionsPanel = document.getElementById('directionsPanel');
            let html = '<div class="space-y-2">';
            
            // Add route summary at top
            const distance = document.getElementById('routeDistance').textContent;
            const duration = document.getElementById('routeDuration').textContent;
            
            html += `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-blue-800">Route Summary</span>
                        <span class="text-blue-600">${distance} • ${duration}</span>
                    </div>
                    <div class="text-xs text-blue-600 mt-1">
                        From: ${technicianName}<br>
                        To: ${customerName}
                    </div>
                </div>
            `;
            
            instructions.forEach((instruction, index) => {
                const distance = instruction.distance > 1000 
                    ? (instruction.distance / 1000).toFixed(1) + ' km'
                    : Math.round(instruction.distance) + ' m';
                
                html += `
                    <div class="flex items-start gap-3 p-2 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 rounded">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full text-xs flex items-center justify-center font-semibold mt-0.5">
                            ${index + 1}
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-800" style="margin-bottom: 2px;">${instruction.text}</div>
                            <div class="text-xs text-gray-500">${distance}</div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            directionsPanel.innerHTML = html;
        }

        function showMapError(message) {
            document.getElementById('mapLoading').style.display = 'none';
            document.getElementById('mapError').style.display = 'flex';
            document.getElementById('errorMessage').textContent = message;
        }

        // Event listeners
        document.getElementById('refreshMap')?.addEventListener('click', function() {
            document.getElementById('mapLoading').style.display = 'flex';
            document.getElementById('mapError').style.display = 'none';
            setTimeout(() => {
                if (map) {
                    map.remove();
                }
                if (routeControl) {
                    routeControl.remove();
                }
                initMap();
            }, 500);
        });

        document.getElementById('fitToRoute')?.addEventListener('click', function() {
            if (map && routeControl) {
                const bounds = L.latLngBounds([
                    [techLat, techLon],
                    [custLat, custLon]
                ]);
                map.fitBounds(bounds, { padding: [20, 20] });
            }
        });

        document.getElementById('retryMap')?.addEventListener('click', function() {
            document.getElementById('mapError').style.display = 'none';
            document.getElementById('mapLoading').style.display = 'flex';
            setTimeout(initMap, 500);
        });

        // Initialize map
        initMap();
    }
});
</script>

<style>
/* Fix z-index issues and layout */
#map {
    position: relative;
    z-index: 1;
}

#mapLoading, #mapError {
    z-index: 1000;
}

.leaflet-container {
    font-family: inherit;
}

.leaflet-control-container {
    z-index: 1000;
}

.leaflet-routing-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
}

/* Ensure proper spacing */
.bg-white.rounded-2xl {
    margin-bottom: 1.5rem;
}

/* Fix loading overlay positioning */
#mapLoading, #mapError {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom marker styles */
.technician-marker, .customer-marker {
    z-index: 1000;
}

/* Directions panel styling */
#directionsPanel {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

#directionsPanel::-webkit-scrollbar {
    width: 6px;
}

#directionsPanel::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#directionsPanel::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#directionsPanel::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Photo Modal Styles */
#photoModal {
    backdrop-filter: blur(4px);
    transition: opacity 0.3s ease;
}

#modalImage {
    transition: transform 0.3s ease;
}

#prevPhoto, #nextPhoto, #closeModal {
    backdrop-filter: blur(4px);
}

#prevPhoto:hover, #nextPhoto:hover, #closeModal:hover {
    background-color: rgba(0, 0, 0, 0.7);
}

/* Responsive improvements */
@media (max-width: 640px) {
    .bg-white.rounded-2xl {
        margin-bottom: 1rem;
    }
    
    #map {
        height: 300px;
    }
    
    .grid.grid-cols-1.md\:grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    #prevPhoto, #nextPhoto {
        padding: 2px;
    }
    
    #prevPhoto svg, #nextPhoto svg, #closeModal svg {
        width: 4px;
        height: 4px;
    }
}
</style>

@endsection