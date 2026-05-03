@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50/20 to-lime-50/10 py-8 px-4 sm:px-6 lg:px-8"
     x-data="userForm()">
<script>
function userForm() {
    return {
        passwordResetLoading: false,
        async sendPasswordReset() {
            this.passwordResetLoading = true;

            try {
                const response = await fetch('{{ route("users.send-password-reset", $user->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    // Show success message
                    alert('New password has been sent to the user\'s email address!');
                } else {
                    throw new Error('Failed to send password reset');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to send password reset. Please try again.');
            } finally {
                this.passwordResetLoading = false;
            }
        }
    }
}
</script>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-8 animate-slide-up">
            <!-- Current User Info -->
            <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                    <p class="text-slate-500 text-sm">{{ $user->email }}</p>
                    <p class="text-xs text-slate-400">Last updated: {{ $user->updated_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 animate-fade-in-down">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 animate-shake">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-rose-800 font-medium text-sm mb-2">Please fix the following errors:</p>
                            <ul class="text-rose-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center gap-2">
                                        <span class="w-1 h-1 bg-rose-400 rounded-full"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif



            <!-- Form -->
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Full Name
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-3.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 placeholder:text-slate-400"
                           placeholder="Enter full name"
                           required>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-3.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 placeholder:text-slate-400"
                           placeholder="Enter email address"
                           required>
                </div>

                <!-- Role Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        User Role
                    </label>
                    <select name="role" 
                            class="w-full px-4 py-3.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 appearance-none cursor-pointer">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Member</option>
                        <option value="technician" {{ old('role', $user->role) === 'technician' ? 'selected' : '' }}>Technician</option>
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <!-- Role Description -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <h4 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Role Permissions
                    </h4>
                    <div class="text-xs text-slate-600 space-y-1">
                        <p class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-400 rounded-full"></span> <strong>Admin:</strong> Full system access and user management</p>
                        <p class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-400 rounded-full"></span> <strong>Staff:</strong> Limited administrative access</p>
                        <p class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-400 rounded-full"></span> <strong>Technician:</strong> Service job management only</p>
                        <p class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-400 rounded-full"></span> <strong>Customer:</strong> Basic user access</p>
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Password Management
                    </h4>
                    <p class="text-xs text-blue-700 mb-3">Send a new auto-generated password to the user's email address.</p>

                    <!-- Password Reset Button -->
                    <button type="button"
                            @click="sendPasswordReset()"
                            :disabled="passwordResetLoading"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                        <svg x-show="passwordResetLoading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <svg x-show="!passwordResetLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="passwordResetLoading ? 'Sending...' : 'Send New Password'"></span>
                    </button>
                </div>



                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('users.index') }}" 
                       class="group bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 px-6 py-3.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-2 sm:order-1">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Users
                    </a>
                    
                    <button type="submit" 
                            class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-1 sm:order-2"
                            :disabled="loading">
                        <svg x-show="!loading" class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <span x-text="loading ? 'Updating...' : 'Update User'"></span>
                        <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 bg-amber-50/50 border border-amber-200 rounded-2xl p-6 animate-fade-in" style="animation-delay: 0.3s">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-amber-900">Security Notice</h3>
                    <ul class="text-sm text-amber-700 space-y-1">
                        <li class="flex items-center gap-2">• Role changes take effect immediately</li>
                        <li class="flex items-center gap-2">• Review permissions before updating roles</li>
                        <li class="flex items-center gap-2">• Users will be logged out on role changes</li>
                        <li class="flex items-center gap-2">• Monitor user activity after permission updates</li>
                    </ul>
                </div>
            </div>
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

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
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

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    /* Custom select arrow */
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