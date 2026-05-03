    @extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="max-w-4xl mx-auto font-sans text-gray-800">
    <div class="bg-white shadow-md rounded-xl border border-gray-200">

        <!-- Header -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <h1 class="text-xl font-semibold text-gray-800">Edit Customer</h1>
            <p class="text-sm text-gray-500">Update customer details.</p>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-8" x-data="smartAddress()" x-init="init()">
            @if(session('success'))
                <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Password Reset Notice -->
            <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="font-semibold">Important Notice</span>
                </div>
                <p>Updating this customer will automatically generate a new random password and send it to their email address. The customer will receive their new login credentials via email.</p>
            </div>

            <form method="POST" action="{{ route('customers.update', $customer->id) }}" class="space-y-8" id="customerForm"
                  :class="showModal ? 'opacity-50 pointer-events-none' : ''">
                @csrf
                @method('PUT')

                <!-- Personal Info -->
                <section>
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->user->name) }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->user->email) }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold">Status *</label>
                            <select id="status" name="status" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ $customer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $customer->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                </section>



                <!-- Smart Address Section -->
                <section>
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-medium text-gray-700">Address Information</h2>
                        <button type="button" @click="openModal" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 text-sm font-medium flex items-center gap-2 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Use Smart Address Finder
                        </button>
                    </div>

                    <!-- Address Display -->
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 p-6 rounded-xl border border-gray-200/60 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Region:</span>
                                    <span x-text="region || 'Not selected'" :class="region ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="region" x-model="region">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Province:</span>
                                    <span x-text="province || 'Not selected'" :class="province ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="province" x-model="province">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Municipality:</span>
                                    <span x-text="municipality || 'Not selected'" :class="municipality ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="municipality" x-model="municipality">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Barangay:</span>
                                    <span x-text="barangay || 'Not selected'" :class="barangay ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="barangay" x-model="barangay">
                                </div>
                            </div>
                            <div class="md:col-span-2 flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="font-semibold text-gray-600">Street:</span>
                                    <span x-text="street || 'Not specified'" :class="street ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="street" x-model="street">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">ZIP Code:</span>
                                    <span x-text="zipCode || '8105'" :class="zipCode ? 'text-gray-900 font-medium' : 'text-gray-400'"></span>
                                    <input type="hidden" name="zip_code" x-model="zipCode">
                                </div>
                            </div>
                        </div>

                        <!-- Full Address Preview -->
                        <div class="mt-4 p-4 bg-white/80 rounded-xl border border-blue-200/50 shadow-sm" x-show="hasAddress()">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-700">Complete Address</span>
                            </div>
                            <p x-text="getFullAddress()" class="text-gray-800 text-sm leading-relaxed pl-9"></p>
                        </div>

                        <!-- Coordinates -->
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm" x-show="hasCoordinates()">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Latitude:</span>
                                    <span x-text="latitude" class="text-green-600 font-mono font-medium"></span>
                                    <input type="hidden" name="latitude" x-model="latitude">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">Longitude:</span>
                                    <span x-text="longitude" class="text-green-600 font-mono font-medium"></span>
                                    <input type="hidden" name="longitude" x-model="longitude">
                                </div>
                            </div>
                        </div>

                        <!-- No Address Selected Message -->
                        <div class="mt-6 text-center py-6 text-gray-500" x-show="!hasAddress()">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">No address selected yet</p>
                            <p class="text-xs text-gray-500 mt-1">Click the button above to set an address using our smart finder</p>
                        </div>
                    </div>

                    <!-- Map Display -->
                    <div id="map" class="mt-4 h-64 rounded-xl border border-gray-300 shadow-sm" x-show="hasCoordinates()"></div>
                </section>

                <!-- Additional Info -->
                <section>
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Additional Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="dob" class="block text-sm font-semibold">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob', $customer->dob?->format('Y-m-d')) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-semibold">Gender</label>
                            <select id="gender" name="gender"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" {{ $customer->gender == '' ? 'selected' : '' }}>—</option>
                                <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $customer->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('customers.send-password-reset', $customer->id) }}" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2"
                                    onclick="return confirm('Are you sure you want to send a password reset email to this customer?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Send Password Reset
                            </button>
                        </form>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('customers.list') }}"
                           class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">
                           Cancel
                        </a>
                        <a href="{{ route('customers.show', $customer->id) }}"
                           class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            View Customer
                        </a>
                        <button type="submit"
                                class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            Update Customer
                        </button>
                    </div>
                </div>
            </form>

            <!-- Premium Smart Address Modal -->
            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                 
                <!-- Backdrop with blurred system background -->
                <div class="absolute inset-0 bg-gray-600/20 backdrop-blur-md transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="relative bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden transform transition-all border border-white/20"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="closeModal">
                     
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600/90 via-indigo-600/90 to-purple-600/90 backdrop-blur-md text-white p-6 border-b border-white/20">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">Smart Address Finder</h3>
                                        <p class="text-blue-100 mt-1 text-sm">Pinpoint your exact location in Panabo City</p>
                                    </div>
                                </div>
                            </div>
                            <button @click="closeModal" 
                                    class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-all duration-200 backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6 max-h-[55vh] overflow-y-auto bg-transparent">
                        <!-- Location Badges -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Region</p>
                                        <p class="text-sm font-medium text-blue-900">Region XI – Davao Region</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Province</p>
                                        <p class="text-sm font-medium text-green-900">Davao del Norte</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-700 uppercase tracking-wide">Municipality</p>
                                        <p class="text-sm font-medium text-purple-900">Panabo City</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barangay Selection -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <div class="w-5 h-5 bg-orange-500 rounded flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                Barangay
                                <span class="text-red-500">*</span>
                            </label>
                            <select x-model="selectedBarangay" @change="onBarangayChange" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm">
                                <option value="">Select your barangay</option>
                                <template x-for="barangay in barangays" :key="barangay">
                                    <option x-text="barangay" :value="barangay"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Street Input -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <div class="w-5 h-5 bg-red-500 rounded flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                Street Name / Building / Landmark
                            </label>
                            <input type="text" x-model="streetInput" @input="onStreetInput" 
                                   placeholder="e.g., Upper Licanan-Waterfall Road, Purok 5, Near Panabo City Hall"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm placeholder-gray-400">
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Start typing to search for streets in the selected barangay
                            </p>
                        </div>

                        <!-- Street Suggestions -->
                        <div x-show="streetSuggestions.length > 0" class="space-y-3" x-cloak>
                            <label class="block text-sm font-semibold text-gray-700">Street Suggestions</label>
                            <div class="border-2 border-gray-200 rounded-xl divide-y divide-gray-100 max-h-48 overflow-y-auto bg-white shadow-sm">
                                <template x-for="suggestion in streetSuggestions" :key="suggestion">
                                    <button type="button" 
                                            @click="selectStreetSuggestion(suggestion)"
                                            class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-all duration-200 text-sm flex items-center gap-3 group">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                        </div>
                                        <span x-text="suggestion" class="text-gray-700 group-hover:text-blue-700 transition-colors font-medium"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Address Preview -->
                        <div x-show="selectedBarangay" class="bg-gradient-to-r from-emerald-50 to-green-50 p-4 rounded-xl border-2 border-emerald-200" x-cloak>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-emerald-900">Address Preview</h4>
                            </div>
                            <p x-text="getAddressPreview()" class="text-emerald-800 text-sm leading-relaxed pl-11"></p>
                        </div>

                        <!-- Mini Map Preview -->
                        <div x-show="selectedBarangay" class="border-2 border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm" x-cloak>
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    Location Preview
                                </h4>
                            </div>
                            <div id="modalMap" class="h-48"></div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 backdrop-blur-md px-6 py-4 border-t border-gray-200/50 flex justify-between items-center">
                        <div class="text-sm text-gray-600 flex items-center gap-2" x-show="selectedBarangay">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span class="font-semibold">Coordinates:</span>
                            <span x-text="latitude && longitude ? latitude + ', ' + longitude : 'Calculating...'" 
                                  :class="latitude && longitude ? 'text-green-600 font-mono font-medium' : 'text-gray-500'"></span>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="closeModal" 
                                    class="px-5 py-2.5 text-gray-700 hover:bg-white rounded-xl border-2 border-gray-300 transition-all duration-200 font-medium hover:shadow-sm">
                                Cancel
                            </button>
                            <button type="button" @click="applyAddress" :disabled="!selectedBarangay"
                                    :class="selectedBarangay ? 
                                    'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-md hover:shadow-lg' : 
                                    'bg-gray-400 cursor-not-allowed text-gray-200'"
                                    class="px-6 py-2.5 rounded-xl font-medium transition-all duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="selectedBarangay">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Apply Address
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet for Maps --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    [x-cloak] { display: none !important; }
    .leaflet-container { 
        font-family: inherit !important;
        z-index: 1;
    }
</style>

<script>
function smartAddress() {
    return {
        showModal: false,
        region: '{{ old('region', $customer->region) }}' || 'Region XI – Davao Region',
        province: '{{ old('province', $customer->province) }}' || 'Davao del Norte',
        municipality: '{{ old('municipality', $customer->municipality) }}' || 'Panabo City',
        barangay: '{{ old('barangay', $customer->barangay) }}',
        street: '{{ old('street', $customer->street) }}',
        zipCode: '{{ old('zip_code', $customer->zip_code) }}' || '8105',
        latitude: '{{ old('latitude', $customer->latitude) }}',
        longitude: '{{ old('longitude', $customer->longitude) }}',
        selectedBarangay: '',
        streetInput: '',
        streetSuggestions: [],
        map: null,
        modalMap: null,
        marker: null,
        modalMarker: null,

        // Panabo City Barangays
        barangays: [
            'A. O. Floirendo',
            'Datu Abdul Dadia',
            'Buenavista',
            'Cacao',
            'Cagangohan',
            'Consolacion',
            'Dapco',
            'Gredu (Poblacion)',
            'J.P. Laurel',
            'Kasilak',
            'Katipunan',
            'Katualan',
            'Kauswagan',
            'Kiotoy',
            'Little Panay',
            'Lower Panaga (Roxas)',
            'Mabunao',
            'Maduao',
            'Malativas',
            'Manay',
            'Nanyo',
            'New Malaga (Dalisay)',
            'New Malitbog',
            'New Pandan (Poblacion)',
            'New Visayas',
            'Quezon',
            'Salvacion',
            'San Francisco (Poblacion)',
            'San Nicolas',
            'San Pedro',
            'San Roque',
            'San Vicente',
            'Santa Cruz',
            'Santo Niño (Poblacion)',
            'Sindaton',
            'Southern Davao',
            'Tagpore',
            'Tibungol',
            'Upper Licanan',
            'Waterfall'
        ],

        // Common streets in Panabo
        commonStreets: {
            'Upper Licanan': [
                'Upper Licanan-Waterfall Road',
                'Purok 1 Upper Licanan',
                'Purok 2 Upper Licanan',
                'Purok 3 Upper Licanan',
                'Purok 4 Upper Licanan',
                'Purok 5 Upper Licanan',
                'Upper Licanan Main Road'
            ],
            'Gredu (Poblacion)': [
                'Rizal Street',
                'Burgos Street',
                'Mabini Street',
                'Luna Street',
                'Poblacion Road',
                'National Highway'
            ],
            'Santo Niño (Poblacion)': [
                'Santo Niño Street',
                'Church Road',
                'Market Road',
                'Poblacion Area'
            ],
            'New Pandan (Poblacion)': [
                'New Pandan Road',
                'Purok Bagong Silang',
                'Purok Masagana'
            ],
            'Waterfall': [
                'Waterfall Road',
                'Purok 1 Waterfall',
                'Purok 2 Waterfall',
                'Waterfall Proper'
            ]
        },

        init() {
            // Pre-fill the selected barangay and street if editing existing data
            if (this.barangay) {
                this.selectedBarangay = this.barangay;
            }
            if (this.street) {
                this.streetInput = this.street;
            }
            
            // Initialize main map only when we have coordinates
            if (this.latitude && this.longitude) {
                this.initMainMap();
            }
        },

        initMainMap() {
            if (this.map) return;
            
            const initialLat = this.latitude ? parseFloat(this.latitude) : 7.3081;
            const initialLng = this.longitude ? parseFloat(this.longitude) : 125.6842;
            
            this.map = L.map('map').setView([initialLat, initialLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            if (this.latitude && this.longitude) {
                this.marker = L.marker([initialLat, initialLng]).addTo(this.map);
                this.map.setView([initialLat, initialLng], 15);
            }
        },

        initModalMap() {
            if (this.modalMap) {
                this.modalMap.remove();
            }

            const initialLat = this.latitude ? parseFloat(this.latitude) : 7.3081;
            const initialLng = this.longitude ? parseFloat(this.longitude) : 125.6842;

            this.modalMap = L.map('modalMap').setView([initialLat, initialLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.modalMap);

            this.modalMarker = L.marker([initialLat, initialLng], { 
                draggable: true 
            }).addTo(this.modalMap);

            this.modalMarker.on('dragend', () => {
                const pos = this.modalMarker.getLatLng();
                this.latitude = pos.lat.toFixed(6);
                this.longitude = pos.lng.toFixed(6);
            });
        },

        openModal() {
            this.showModal = true;
            // Initialize modal map after a brief delay to ensure DOM is ready
            setTimeout(() => {
                this.initModalMap();
            }, 100);
        },

        closeModal() {
            this.showModal = false;
            this.streetInput = '';
            this.streetSuggestions = [];
        },

        onBarangayChange() {
            this.streetInput = '';
            this.streetSuggestions = [];
            this.updateModalMapLocation();
        },

        onStreetInput() {
            if (this.selectedBarangay && this.streetInput.length > 2) {
                this.searchStreetSuggestions();
            } else {
                this.streetSuggestions = [];
            }
        },

        searchStreetSuggestions() {
            const query = this.streetInput.toLowerCase();
            let suggestions = [];

            // Get suggestions from common streets for the selected barangay
            if (this.commonStreets[this.selectedBarangay]) {
                suggestions = this.commonStreets[this.selectedBarangay].filter(street => 
                    street.toLowerCase().includes(query)
                );
            }

            // Also include generic suggestions based on input
            if (suggestions.length === 0 && this.streetInput.length > 3) {
                suggestions = [
                    `${this.streetInput} Street`,
                    `${this.streetInput} Road`,
                    `${this.streetInput} Avenue`,
                    `Purok ${this.streetInput}`,
                    `Near ${this.streetInput}`
                ];
            }

            this.streetSuggestions = suggestions.slice(0, 5);
        },

        selectStreetSuggestion(suggestion) {
            this.streetInput = suggestion;
            this.streetSuggestions = [];
            this.updateModalMapLocation();
        },

        async updateModalMapLocation() {
            if (!this.selectedBarangay) return;

            // Build search query for geocoding
            const searchQuery = `${this.streetInput}, ${this.selectedBarangay}, Panabo City, Davao del Norte, Philippines`;
            
            const [lat, lng] = await this.geocodeAddress(searchQuery);
            
            if (lat && lng) {
                this.modalMap.setView([lat, lng], 15);
                this.modalMarker.setLatLng([lat, lng]);
                this.latitude = lat;
                this.longitude = lng;
            } else {
                // Fallback to barangay center if street not found
                const [barangayLat, barangayLng] = await this.geocodeAddress(`${this.selectedBarangay}, Panabo City, Philippines`);
                if (barangayLat && barangayLng) {
                    this.modalMap.setView([barangayLat, barangayLng], 14);
                    this.modalMarker.setLatLng([barangayLat, barangayLng]);
                    this.latitude = barangayLat;
                    this.longitude = barangayLng;
                }
            }
        },

        async geocodeAddress(address) {
            if (!address) return [null, null];

            try {
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`;
                
                const response = await fetch(url, {
                    headers: {
                        'User-Agent': 'YourApp/1.0 (your-email@domain.com)',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data && data.length > 0) {
                    return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                }
            } catch (error) {
                console.error('Geocoding error:', error);
            }

            return [null, null];
        },

        applyAddress() {
            if (!this.selectedBarangay) return;

            this.barangay = this.selectedBarangay;
            this.street = this.streetInput;
            this.zipCode = '8105';

            // Initialize main map with the selected location
            this.initMainMap();
            if (this.map && this.latitude && this.longitude) {
                if (this.marker) {
                    this.marker.setLatLng([this.latitude, this.longitude]);
                } else {
                    this.marker = L.marker([this.latitude, this.longitude]).addTo(this.map);
                }
                this.map.setView([this.latitude, this.longitude], 15);
            }
            
            this.closeModal();
        },

        getAddressPreview() {
            return `${this.streetInput ? this.streetInput + ', ' : ''}${this.selectedBarangay}, Panabo City, Davao del Norte, Region XI – Davao Region, 8105`;
        },

        getFullAddress() {
            return `${this.street ? this.street + ', ' : ''}${this.barangay ? this.barangay + ', ' : ''}${this.municipality}, ${this.province}, ${this.region}${this.zipCode ? ', ' + this.zipCode : ''}`;
        },

        hasAddress() {
            return this.barangay || this.street;
        },

        hasCoordinates() {
            return this.latitude && this.longitude;
        }
    }
}
</script>
@endsection