@extends('layouts.admin')

@section('title', 'New Service Request')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                            <x-lucide-clipboard-plus class="w-6 h-6 text-white"/>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Create New Service Request</h2>
                            <p class="text-blue-100 mt-1">Fill in the details to create a new service request</p>
                        </div>
                    </div>
                    <a href="{{ route('service_requests.index') }}" 
                       class="group flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm">
                        <x-lucide-arrow-left class="w-4 h-4 transition-transform group-hover:-translate-x-1"/>
                        <span>Back to Requests</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <form method="POST" action="{{ route('service_requests.store') }}" class="p-8 space-y-8">
                @csrf

                <!-- Customer Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <x-lucide-user class="w-5 h-5 text-blue-600"/>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>

                    <!-- Customer Selection -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Select Customer
                            </label>
                            <span class="text-xs text-gray-500">Search by name, email, or phone</span>
                        </div>

                        <!-- Customer Search Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-search class="w-4 h-4 text-gray-400"/>
                            </div>
                            <input type="text" 
                                   id="customerSearch" 
                                   placeholder="Type to search customers..."
                                   class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            
                            <!-- Loading Indicator -->
                            <div id="customerLoading" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center">
                                <x-lucide-loader class="w-4 h-4 animate-spin text-gray-400"/>
                            </div>
                            
                            <!-- Dropdown Results -->
                            <div id="customerResults" class="hidden absolute z-40 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <!-- Results will appear here -->
                            </div>
                        </div>

                        <!-- Quick Customer Selection -->
                        <div id="recentCustomers" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($recentCustomers as $customer)
                                <button type="button" 
                                        class="customer-quick-select text-left p-3 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-all duration-200"
                                        data-customer='@json($customer)'>
                                    <div class="font-medium text-gray-900">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer['email'] }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $customer['phone'] }}</div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Clear Selection Button -->
                        <button type="button" 
                                id="clearCustomer" 
                                class="hidden w-full py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center gap-2">
                            <x-lucide-x class="w-4 h-4"/>
                            <span>Clear Customer Selection</span>
                        </button>
                    </div>

                    <!-- Customer Details Form (Auto-filled) -->
                    <div id="customerDetails" class="hidden space-y-4 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 transition-all duration-300 slide-in">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-user-check class="w-5 h-5 text-blue-600"/>
                            <h4 class="font-semibold text-gray-900">Selected Customer Details</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-lucide-user class="w-4 h-4 text-gray-400"/>
                                    </div>
                                    <input type="text" 
                                           name="customer_name" 
                                           id="customer_name"
                                           readonly
                                           class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-lucide-phone class="w-4 h-4 text-gray-400"/>
                                    </div>
                                    <input type="tel" 
                                           name="phone" 
                                           id="customer_phone"
                                           readonly
                                           class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-lucide-mail class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="customer_email"
                                       readonly
                                       class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-lucide-map-pin class="w-4 h-4 text-gray-400"/>
                                </div>
                                <input type="text" 
                                       name="address" 
                                       id="customer_address"
                                       readonly
                                       class="pl-10 w-full border border-blue-200 bg-white/80 px-4 py-2 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Hidden customer ID -->
                        <input type="hidden" name="customer_id" id="customer_id">
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
                        <label class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                            <span>Full Address</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-map-pin class="w-4 h-4 text-gray-400"/>
                            </div>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" 
                                   required placeholder="Street, City, Country..." 
                                   class="pl-10 w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        </div>
                        <p id="addressStatus" class="text-sm text-gray-500 mt-2 flex items-center gap-2 transition-all duration-300">
                            <x-lucide-info class="w-4 h-4"/>
                            <span>Enter full address to preview map location</span>
                        </p>
                    </div>

                    <!-- Hidden inputs to store lat/lon -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    <!-- Map Preview -->
                    <div id="mapPreviewContainer" class="mt-4 hidden rounded-2xl border border-gray-200 overflow-hidden shadow-lg transition-all duration-500 relative z-10">
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
                        <label class="block text-sm font-medium text-gray-700 flex items-center gap-1">
                            <span>Service Type</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-600">
                                <x-lucide-wrench class="w-4 h-4 text-gray-400"/>
                            </div>
                            <select name="service_type" required 
                                    class="pl-10 appearance-none w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md bg-white">
                                <option value="">Select Service Type</option>
                                <option value="Installation" {{ old('service_type') == 'Installation' ? 'selected' : '' }}>Installation</option>
                                <option value="Repair" {{ old('service_type') == 'Repair' ? 'selected' : '' }}>Repair</option>
                                <option value="Maintenance" {{ old('service_type') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Inspection" {{ old('service_type') == 'Inspection' ? 'selected' : '' }}>Inspection</option>
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
                                      placeholder="Add any additional details, special instructions, or requirements...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500 flex items-center gap-2">
                            <x-lucide-shield-check class="w-4 h-4"/>
                            <span>All information is securely stored</span>
                        </div>
                        <button type="submit" 
                                class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-4 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <x-lucide-save class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"/>
                            <span class="relative">Create Service Request</span>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <x-lucide-check class="w-4 h-4 transform -translate-y-2 group-hover:translate-y-0 transition-transform duration-300"/>
                            </div>
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

<!-- Custom Styles -->
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

@keyframes pulseSuccess {
    0%, 100% { background-color: #f0f9ff; }
    50% { background-color: #e0f2fe; }
}

.slide-in {
    animation: slideInUp 0.6s ease-out;
}

.success-pulse {
    animation: pulseSuccess 2s ease-in-out;
}

/* Z-index fixes to prevent overlapping */
#customerResults {
    z-index: 40 !important;
}

#mapPreviewContainer {
    z-index: 10 !important;
}

.leaflet-container {
    z-index: 1 !important;
}

.leaflet-popup,
.leaflet-control {
    z-index: 20 !important;
}

/* Ensure header and navigation stay on top */
.bg-white.rounded-2xl.shadow-xl {
    position: relative;
    z-index: 30;
}

/* Custom select arrow */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Ensure proper stacking context */
.min-h-screen {
    position: relative;
    z-index: 1;
}

.max-w-4xl {
    position: relative;
    z-index: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Customer Search Functionality
    const customerSearch = document.getElementById('customerSearch');
    const customerResults = document.getElementById('customerResults');
    const customerLoading = document.getElementById('customerLoading');
    const customerDetails = document.getElementById('customerDetails');
    const clearCustomerBtn = document.getElementById('clearCustomer');
    const recentCustomers = document.getElementById('recentCustomers');
    let searchTimeout;

    // Customer Search with Debouncing
    customerSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            customerResults.classList.add('hidden');
            customerLoading.classList.add('hidden');
            return;
        }

        customerLoading.classList.remove('hidden');
        customerResults.classList.add('hidden');

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/customers/search?q=${encodeURIComponent(query)}`);
                const customers = await response.json();
                
                displayCustomerResults(customers);
                customerLoading.classList.add('hidden');
            } catch (error) {
                console.error('Error searching customers:', error);
                customerLoading.classList.add('hidden');
                showSearchError();
            }
        }, 500);
    });

    function displayCustomerResults(customers) {
        customerResults.innerHTML = '';
        
        if (customers.length === 0) {
            customerResults.innerHTML = `
                <div class="px-4 py-6 text-center text-gray-500">
                    <x-lucide-users class="w-8 h-8 mx-auto mb-2 text-gray-400"/>
                    <p>No customers found</p>
                    <p class="text-sm mt-1">Try a different search term</p>
                </div>
            `;
        } else {
            customers.forEach(customer => {
                const customerElement = document.createElement('div');
                customerElement.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-200 group';
                customerElement.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 group-hover:text-blue-700">${customer.name}</div>
                            <div class="text-sm text-gray-500 mt-1">${customer.email}</div>
                            <div class="text-sm text-gray-500">${customer.phone || 'No phone'}</div>
                            <div class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                <x-lucide-map-pin class="w-3 h-3"/>
                                ${customer.address || 'No address'}
                            </div>
                        </div>
                        <div class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">
                            ${customer.account_number}
                        </div>
                    </div>
                `;
                customerElement.addEventListener('click', () => selectCustomer(customer));
                customerResults.appendChild(customerElement);
            });
        }
        
        customerResults.classList.remove('hidden');
    }

    function showSearchError() {
        customerResults.innerHTML = `
            <div class="px-4 py-6 text-center text-red-500">
                <x-lucide-wifi-off class="w-8 h-8 mx-auto mb-2"/>
                <p>Search failed</p>
                <p class="text-sm mt-1">Please try again</p>
            </div>
        `;
        customerResults.classList.remove('hidden');
    }

    // Quick Select from Recent Customers
    document.querySelectorAll('.customer-quick-select').forEach(button => {
        button.addEventListener('click', function() {
            const customer = JSON.parse(this.getAttribute('data-customer'));
            selectCustomer(customer);
        });
    });

    function selectCustomer(customer) {
        // Fill the form fields
        document.getElementById('customer_name').value = customer.name;
        document.getElementById('customer_phone').value = customer.phone || '';
        document.getElementById('customer_email').value = customer.email;
        document.getElementById('customer_address').value = customer.address || '';
        document.getElementById('customer_id').value = customer.id;
        
        // Update the main address input for map functionality
        const addressInput = document.getElementById('address');
        if (addressInput && customer.address) {
            addressInput.value = customer.address;
            
            // If customer has coordinates, use them directly
            if (customer.latitude && customer.longitude) {
                document.getElementById('latitude').value = customer.latitude;
                document.getElementById('longitude').value = customer.longitude;
                showMap(customer.latitude, customer.longitude);
            } else {
                // Try multiple geocoding attempts with different address formats
                geocodeAddressWithFallbacks(customer.address);
            }
        }
        
        // Show customer details and clear button, hide recent customers
        customerDetails.classList.remove('hidden');
        clearCustomerBtn.classList.remove('hidden');
        recentCustomers.classList.add('hidden');
        
        // Hide search results and clear search
        customerResults.classList.add('hidden');
        customerSearch.value = '';
        
        // Add success animation
        customerDetails.classList.add('success-pulse');
        setTimeout(() => {
            customerDetails.classList.remove('success-pulse');
        }, 2000);
    }

    // Clear Customer Selection
    clearCustomerBtn.addEventListener('click', function() {
        // Clear all customer fields
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_phone').value = '';
        document.getElementById('customer_email').value = '';
        document.getElementById('customer_address').value = '';
        document.getElementById('customer_id').value = '';
        
        // Hide customer details and clear button, show recent customers
        customerDetails.classList.add('hidden');
        this.classList.add('hidden');
        recentCustomers.classList.remove('hidden');
        
        // Clear the main address input
        const addressInput = document.getElementById('address');
        if (addressInput) {
            addressInput.value = '';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
            customerResults.classList.add('hidden');
        }
    });

    // Keyboard navigation for dropdown
    customerSearch.addEventListener('keydown', function(e) {
        const results = customerResults.querySelectorAll('div');
        const activeResult = customerResults.querySelector('.bg-blue-50');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            navigateResults(1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            navigateResults(-1);
        } else if (e.key === 'Enter' && activeResult) {
            e.preventDefault();
            activeResult.click();
        }
    });

    function navigateResults(direction) {
        const results = customerResults.querySelectorAll('div');
        const activeIndex = Array.from(results).findIndex(r => r.classList.contains('bg-blue-50'));
        
        let nextIndex;
        if (activeIndex === -1) {
            nextIndex = direction === 1 ? 0 : results.length - 1;
        } else {
            nextIndex = activeIndex + direction;
            if (nextIndex < 0) nextIndex = results.length - 1;
            if (nextIndex >= results.length) nextIndex = 0;
        }
        
        results.forEach(r => r.classList.remove('bg-blue-50'));
        if (results[nextIndex]) {
            results[nextIndex].classList.add('bg-blue-50');
            results[nextIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    // Enhanced address and map functionality
    const addressInput = document.getElementById('address');
    const statusText = document.getElementById('addressStatus');
    const mapContainer = document.getElementById('mapPreviewContainer');
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    let map, marker;

    // Function to try multiple geocoding attempts with different address formats
    async function geocodeAddressWithFallbacks(fullAddress) {
        statusText.innerHTML = `<x-lucide-loader class="w-4 h-4 animate-spin"/> Searching for address...`;
        statusText.classList.remove('text-green-600', 'text-red-600');
        statusText.classList.add('text-blue-600');

        // Try different address formats
        const addressAttempts = [
            fullAddress, // Original full address
            extractCityAndRegion(fullAddress), // City + Region only
            extractCityOnly(fullAddress), // City only
            extractGeneralArea(fullAddress) // General area
        ];

        // Remove duplicates and empty values
        const uniqueAttempts = [...new Set(addressAttempts.filter(addr => addr && addr.length > 2))];

        console.log('Trying address formats:', uniqueAttempts);

        for (const attempt of uniqueAttempts) {
            try {
                const result = await geocodeAddress(attempt);
                if (result.success) {
                    console.log(`✅ Geocoding successful with: ${attempt}`);
                    showMap(result.lat, result.lon);
                    return;
                }
            } catch (error) {
                console.log(`❌ Geocoding failed for: ${attempt}`);
                continue;
            }
        }

        // If all attempts fail, show error but still allow form submission
        statusText.innerHTML = `<x-lucide-alert-circle class="w-4 h-4"/> Address not precisely located. You can still submit the request.`;
        statusText.classList.remove('text-blue-600', 'text-green-600');
        statusText.classList.add('text-yellow-600');
        mapContainer.classList.add('hidden');
        latInput.value = '';
        lonInput.value = '';
    }

    // Helper function to extract city and region from full address
    function extractCityAndRegion(fullAddress) {
        // Match patterns like "Panabo City, Davao del Norte, Region XI"
        const cityRegionMatch = fullAddress.match(/([^,]+(?:City|Municipality)),\s*([^,]+(?:Province|Region)?)/i);
        if (cityRegionMatch) {
            return `${cityRegionMatch[1].trim()}, ${cityRegionMatch[2].trim()}`;
        }
        
        // Fallback: get last 2 parts of address
        const parts = fullAddress.split(',').map(part => part.trim()).filter(part => part);
        if (parts.length >= 2) {
            return `${parts[parts.length - 2]}, ${parts[parts.length - 1]}`;
        }
        
        return fullAddress;
    }

    // Helper function to extract city only
    function extractCityOnly(fullAddress) {
        // Look for city names ending with "City"
        const cityMatch = fullAddress.match(/([^,]+City)/i);
        if (cityMatch) {
            return cityMatch[1].trim();
        }
        
        // Fallback: get the part before the first comma that contains geographic terms
        const parts = fullAddress.split(',').map(part => part.trim());
        for (const part of parts) {
            if (part.includes('City') || part.includes('Municipality') || part.includes('Town')) {
                return part;
            }
        }
        
        // Last resort: return the first meaningful part
        return parts[0] || fullAddress;
    }

    // Helper function to extract general area
    function extractGeneralArea(fullAddress) {
        // For Davao region, we can use broader areas
        if (fullAddress.includes('Davao')) {
            if (fullAddress.includes('Panabo')) {
                return 'Panabo City, Davao del Norte';
            }
            if (fullAddress.includes('Davao City')) {
                return 'Davao City';
            }
            return 'Davao Region';
        }
        
        // General fallback - get the most recognizable location name
        const parts = fullAddress.split(',').map(part => part.trim());
        for (const part of parts) {
            if (part.includes('City') || part.includes('Municipality') || part.match(/\b(?:Street|Avenue|Road|Barangay)\b/i)) {
                continue;
            }
            if (part.length > 3 && !part.match(/^\d/)) {
                return part;
            }
        }
        
        return parts[0] || fullAddress;
    }

    // Single geocoding attempt
    async function geocodeAddress(query) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=ph`,
                {
                    headers: {
                        'User-Agent': 'PCTVS-Service-System/1.0',
                        'Accept': 'application/json'
                    }
                }
            );
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.length > 0) {
                return {
                    success: true,
                    lat: parseFloat(data[0].lat),
                    lon: parseFloat(data[0].lon)
                };
            } else {
                return { success: false };
            }
        } catch (error) {
            console.error('Geocoding error:', error);
            return { success: false };
        }
    }

    function showMap(lat, lon) {
        // Add success animation
        mapContainer.classList.add('slide-in');
        statusText.innerHTML = `<x-lucide-check class="w-4 h-4 text-green-500"/> Address located. Map preview below.`;
        statusText.classList.add('text-green-600');
        
        mapContainer.classList.remove('hidden');
        latInput.value = lat;
        lonInput.value = lon;

        if (!map) {
            map = L.map('mapPreview').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            marker = L.marker([lat, lon]).addTo(map)
                .bindPopup('Service Location')
                .openPopup();
                
            // Add custom marker icon
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
    }

    let typingTimer;
    const delay = 1000; // Increased delay for better user experience

    addressInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        statusText.innerHTML = `<x-lucide-loader class="w-4 h-4 animate-spin"/> Searching for address...`;
        statusText.classList.remove('text-green-600', 'text-red-600', 'text-yellow-600');
        statusText.classList.add('text-blue-600');
        
        const query = addressInput.value.trim();
        if (!query) {
            statusText.innerHTML = `<x-lucide-info class="w-4 h-4"/> Enter full address to preview map location`;
            statusText.classList.remove('text-blue-600', 'text-green-600', 'text-red-600', 'text-yellow-600');
            statusText.classList.add('text-gray-500');
            mapContainer.classList.add('hidden');
            return;
        }

        typingTimer = setTimeout(async () => {
            await geocodeAddressWithFallbacks(query);
        }, delay);
    });

    // If old lat/lon exist, show map on page load (for validation error reload)
    const oldLat = parseFloat(latInput.value);
    const oldLon = parseFloat(lonInput.value);
    if (oldLat && oldLon) {
        showMap(oldLat, oldLon);
    }

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