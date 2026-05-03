<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCTVS Staff - @yield('title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Pusher meta (for notifications) -->
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}">
    <meta name="user-id" content="{{ Auth::id() }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Laravel Vite Assets -->
    <!-- ✅ For production (Hostinger) -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-BuDOdtjT.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/admin-Ds8aWNW5.css') }}">


<script type="module" src="{{ asset('build/assets/app-BCWiwugg.js') }}"></script>
<script type="module" src="{{ asset('build/assets/admin-Bl842mcv.js') }}"></script>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    @include('layouts.partials.staff_sidebar')

    <!-- Main Content -->
    <div id="mainContent" class="flex-1 pt-24 px-6">

        <!-- Header -->
        @include('layouts.partials.staff_header')

        <!-- Modern Toast Notifications -->
        <div id="toast-container" class="fixed top-6 right-6 z-[9999] space-y-4 max-w-md pointer-events-none">
            {{-- ✅ Success Toast --}}
            @if(session('success'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-500 transform"
                    x-transition:enter-start="opacity-0 translate-x-full scale-90"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-x-full scale-90"
                    class="pointer-events-auto bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-500 text-white p-5 rounded-2xl shadow-2xl backdrop-blur-md border border-emerald-400/30 ring-1 ring-emerald-300/20">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 animate-pulse">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-bold tracking-wide">Success!</p>
                            <p class="text-sm opacity-95 mt-2 leading-relaxed font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-white/80 hover:text-white hover:bg-white/10 rounded-full p-1 transition-all duration-200 hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- ✅ Error Toast --}}
            @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 6000)"
                    x-transition:enter="transition ease-out duration-500 transform"
                    x-transition:enter-start="opacity-0 translate-x-full scale-90"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-x-full scale-90"
                    class="pointer-events-auto bg-gradient-to-r from-red-500 via-red-600 to-rose-500 text-white p-5 rounded-2xl shadow-2xl backdrop-blur-md border border-red-400/30 ring-1 ring-red-300/20">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 animate-bounce">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-bold tracking-wide">Error!</p>
                            <p class="text-sm opacity-95 mt-2 leading-relaxed font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-white/80 hover:text-white hover:bg-white/10 rounded-full p-1 transition-all duration-200 hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- ✅ Info Toast --}}
            @if(session('info'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-500 transform"
                    x-transition:enter-start="opacity-0 translate-x-full scale-90"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-x-full scale-90"
                    class="pointer-events-auto bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-500 text-white p-5 rounded-2xl shadow-2xl backdrop-blur-md border border-blue-400/30 ring-1 ring-blue-300/20">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 animate-pulse">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-bold tracking-wide">Information</p>
                            <p class="text-sm opacity-95 mt-2 leading-relaxed font-medium">{{ session('info') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-white/80 hover:text-white hover:bg-white/10 rounded-full p-1 transition-all duration-200 hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Custom Styles for Sidebar -->
    <style>
        #sidebar {
            width: 256px;
            transition: width 0.3s ease-in-out;
        }

        #sidebar.collapsed {
            width: 64px !important;
        }

        #sidebar.collapsed .sidebar-text {
            opacity: 0;
            pointer-events: none;
            display: none;
            transition: opacity 0.3s ease-in-out;
        }

        #sidebar.collapsed .flex.items-center {
            justify-content: center;
        }

        #sidebar.collapsed .flex.items-center svg {
            margin-right: 0 !important;
        }

        #sidebar.collapsed .flex.items-center.py-2.px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        #mainContent {
            margin-left: 256px;
            transition: margin-left 0.3s ease-in-out;
        }

        #mainHeader {
            left: 256px;
            transition: left 0.3s ease-in-out;
        }
    </style>

    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const mainHeader = document.getElementById('mainHeader');

            // Toggle collapsed class on sidebar
            sidebar.classList.toggle('collapsed');

            // Update positions based on collapsed state
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '64px';
                mainHeader.style.left = '64px';
            } else {
                mainContent.style.marginLeft = '256px';
                mainHeader.style.left = '256px';
            }
        }

        // ✅ Toggle Submenu with Smooth Transition
        window.toggleSubmenu = function(id) {
            const menu = document.getElementById(id);
            if (!menu) return;

            // Add smooth transition classes
            menu.classList.add('transition-all', 'duration-300', 'ease-in-out');

            // Toggle visibility with smooth animation
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                // Force reflow to ensure transition works
                menu.offsetHeight;
                menu.classList.add('opacity-100', 'max-h-96');
            } else {
                menu.classList.remove('opacity-100', 'max-h-96');
                menu.classList.add('opacity-0', 'max-h-0');
                // Hide after transition
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);
            }
        };

        // User Dropdown Menu
        function toggleUserMenu(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('userMenu');
            if (!menu) return;

            menu.classList.toggle('opacity-100');
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('translate-y-0');
            menu.classList.toggle('-translate-y-2');
            menu.classList.toggle('pointer-events-none');
            menu.classList.add('transition-all', 'duration-200', 'ease-out');
        }

        // Close user dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const menu = document.getElementById('userMenu');
            const button = document.getElementById('userMenuButton');
            if (!menu) return;
            if (button.contains(e.target)) return;
            if (!menu.contains(e.target)) {
                menu.classList.add('pointer-events-none', 'opacity-0', '-translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
