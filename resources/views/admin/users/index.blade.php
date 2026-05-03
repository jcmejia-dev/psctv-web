@extends('layouts.admin')

@section('title', 'User Accounts')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 p-6">
    <div class="max-w-7xl mx-auto">



        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8 animate-fade-in-down">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-4-4h-1M9 20H4v-2a3 3 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">User Management</h1>
                    <p class="text-slate-600 mt-1">Manage system users and their permissions</p>
                </div>
            </div>

            <!-- Add User Button -->
            <a href="{{ route('users.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New User
                <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Search & Filter Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 mb-6 animate-slide-up">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
                <!-- Search Input -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search Users</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name or email..."
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50">
                        <svg class="w-4 h-4 absolute left-3 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter by Role</label>
                    <select name="role"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 appearance-none cursor-pointer">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="technician" {{ request('role') === 'technician' ? 'selected' : '' }}>Technician</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Apply Filters
                    </button>
                    <a href="{{ route('users.index') }}"
                       class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 px-6 py-3 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>



        <!-- Users Table -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 overflow-hidden animate-slide-up" style="animation-delay: 0.2s">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100/50 border-b border-slate-200">
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider rounded-tl-xl">#</th>
                            <th class="py-4 px-6 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">User Details</th>
                            <th class="py-4 px-6 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Role</th>
                            <th class="py-4 px-6 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider rounded-tr-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($users as $user)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                <td class="py-4 px-6 text-slate-600 font-medium">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                
                                <!-- User Details -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $user->name }}</p>
                                            <p class="text-slate-500 text-sm">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role Badge -->
                                <td class="py-4 px-6">
                                    @php
                                        $roleStyles = [
                                            'admin'      => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
                                            'staff'      => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                            'technician' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                            'customer'   => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                            'unknown'    => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
                                        ];
                                        $role = $user->role ?? 'unknown';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border {{ $roleStyles[$role]['bg'] }} {{ $roleStyles[$role]['text'] }} {{ $roleStyles[$role]['border'] }} shadow-sm capitalize">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6">
                                    <div class="flex justify-center gap-3">
                                        <!-- Edit Button -->
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                           class="group relative p-2 text-slate-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-110"
                                           title="Edit User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <div class="absolute inset-0 bg-blue-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                        </a>

                                        <!-- Module Access Button (Staff Only) -->
                                        @if($user->role === 'staff')
                                            <a href="{{ route('staff-module-access.edit', $user->id) }}" 
                                               class="group relative p-2 text-slate-400 hover:text-emerald-600 transition-all duration-300 transform hover:scale-110"
                                               title="Manage Module Access">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div class="absolute inset-0 bg-emerald-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="group relative p-2 text-slate-400 hover:text-rose-600 transition-all duration-300 transform hover:scale-110"
                                                    title="Delete User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <div class="absolute inset-0 bg-rose-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-12">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-4-4h-1M9 20H4v-2a3 3 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-lg font-medium text-slate-400 mb-2">No users found</p>
                                        <p class="text-sm text-slate-400">Try adjusting your search or filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-slate-50/50 border-t border-slate-200 px-6 py-4">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-sm text-slate-600">
                        <p class="font-medium">
                            Showing <span class="text-slate-900 font-semibold">{{ $users->firstItem() }}</span> to 
                            <span class="text-slate-900 font-semibold">{{ $users->lastItem() }}</span> of 
                            <span class="text-slate-900 font-semibold">{{ $users->total() }}</span> users
                        </p>

                        <div class="flex items-center gap-1">
                            <!-- Previous Button -->
                            @if ($users->onFirstPage())
                                <span class="px-3 py-2 border border-slate-200 rounded-lg text-slate-400 cursor-not-allowed bg-white/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" 
                                   class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-white hover:border-slate-300 transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            @php
                                $current = $users->currentPage();
                                $last = $users->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $current + 2);
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $users->url(1) }}" 
                                   class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-white hover:border-slate-300 transition-all duration-300 transform hover:scale-105">1</a>
                                @if ($start > 2)
                                    <span class="px-2 text-slate-400">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <span class="px-3 py-2 border border-blue-500 bg-blue-500 text-white rounded-lg font-semibold shadow-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $users->url($page) }}" 
                                       class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-white hover:border-slate-300 transition-all duration-300 transform hover:scale-105">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="px-2 text-slate-400">...</span>
                                @endif
                                <a href="{{ $users->url($last) }}" 
                                   class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-white hover:border-slate-300 transition-all duration-300 transform hover:scale-105">{{ $last }}</a>
                            @endif

                            <!-- Next Button -->
                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" 
                                   class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-white hover:border-slate-300 transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 border border-slate-200 rounded-lg text-slate-400 cursor-not-allowed bg-white/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fade-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    .animate-fade-in-right {
        animation: fade-in-right 0.6s ease-out forwards;
    }

    /* Custom select styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection