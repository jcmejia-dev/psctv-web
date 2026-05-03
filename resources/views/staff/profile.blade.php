@extends('layouts.staff')

@section('title', 'Staff Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                        <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Info Card -->
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-8">
                <!-- Avatar Section -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=120"
                            alt="Avatar" class="w-32 h-32 rounded-3xl shadow-xl border-4 border-white">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h2>
                            <p class="text-lg text-gray-600 mb-3">{{ $user->email }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst($user->role ?? 'Staff') }}
                                </span>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Details Grid -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 p-6 rounded-2xl border border-gray-200/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m0 0l-2-2m2 2l2-2m6-6v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h8a2 2 0 012 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Account Created</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-slate-50 to-gray-50 p-6 rounded-2xl border border-gray-200/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 rounded-xl">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Last Updated</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-slate-50 to-gray-50 p-6 rounded-2xl border border-gray-200/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-purple-100 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Account Status</p>
                            <p class="text-lg font-semibold text-green-600">Active</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Form -->
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-white/20">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Update Profile</h3>
            </div>

            <form action="{{ route('staff.profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">New Password <span class="text-gray-500 font-normal">(optional)</span></label>
                        <input type="password" name="password" placeholder="Enter new password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep your current password</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
