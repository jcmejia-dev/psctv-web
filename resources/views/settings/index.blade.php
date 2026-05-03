@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Theme Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Appearance</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Theme
                                    </label>
                                    <select id="theme" name="theme"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="light" {{ $preferences['theme'] === 'light' ? 'selected' : '' }}>
                                            Light
                                        </option>
                                        <option value="dark" {{ $preferences['theme'] === 'dark' ? 'selected' : '' }}>
                                            Dark
                                        </option>
                                        <option value="system" {{ $preferences['theme'] === 'system' ? 'selected' : '' }}>
                                            System
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Notifications</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Notification Preferences
                                    </label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input id="notifications_email" name="notifications[email]" type="checkbox"
                                                   value="1" {{ ($preferences['notifications']['email'] ?? false) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="notifications_email" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                Email notifications
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="notifications_sms" name="notifications[sms]" type="checkbox"
                                                   value="1" {{ ($preferences['notifications']['sms'] ?? false) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="notifications_sms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                SMS notifications
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="notifications_push" name="notifications[push]" type="checkbox"
                                                   value="1" {{ ($preferences['notifications']['push'] ?? false) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="notifications_push" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                Push notifications
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Language Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Language & Region</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Language
                                    </label>
                                    <select id="language" name="language"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="en" {{ $preferences['language'] === 'en' ? 'selected' : '' }}>
                                            English
                                        </option>
                                        <option value="es" {{ $preferences['language'] === 'es' ? 'selected' : '' }}>
                                            Español
                                        </option>
                                        <option value="fr" {{ $preferences['language'] === 'fr' ? 'selected' : '' }}>
                                            Français
                                        </option>
                                        <option value="tl" {{ $preferences['language'] === 'tl' ? 'selected' : '' }}>
                                            Tagalog
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">System Status</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Database Status -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($systemStatus['database']['status'] === 'healthy')
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            @else
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Database</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $systemStatus['database']['response_time'] ? $systemStatus['database']['response_time'] . 'ms' : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Cache Status -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($systemStatus['cache']['status'] === 'healthy')
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            @else
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Cache</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $systemStatus['cache']['response_time'] ? $systemStatus['cache']['response_time'] . 'ms' : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Queue Status -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($systemStatus['queue']['status'] === 'healthy')
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            @else
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Queue</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ isset($systemStatus['queue']['failed_jobs']) ? $systemStatus['queue']['failed_jobs'] . ' failed' : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($systemStatus['overall'] === 'healthy')
                                                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                                @elseif($systemStatus['overall'] === 'warning')
                                                    <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                                @else
                                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $systemStatus['overall_text'] }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Last checked: {{ \Carbon\Carbon::parse($systemStatus['timestamp'])->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="checkSystemStatus()"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Account Information</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($user->role ?? 'user') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M j, Y') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkSystemStatus() {
            fetch('{{ route("settings.system-status") }}')
                .then(response => response.json())
                .then(data => {
                    // Update the status indicators
                    updateStatusIndicator('database', data.database);
                    updateStatusIndicator('cache', data.cache);
                    updateStatusIndicator('queue', data.queue);
                    updateOverallStatus(data);
                })
                .catch(error => {
                    console.error('Error checking system status:', error);
                });
        }

        function updateStatusIndicator(type, status) {
            const indicator = document.querySelector(`[data-status="${type}"]`);
            if (indicator) {
                const dot = indicator.querySelector('.status-dot');
                const responseTime = indicator.querySelector('.response-time');

                if (status.status === 'healthy') {
                    dot.className = 'status-dot w-3 h-3 bg-green-500 rounded-full';
                } else {
                    dot.className = 'status-dot w-3 h-3 bg-red-500 rounded-full';
                }

                if (responseTime && status.response_time) {
                    responseTime.textContent = status.response_time + 'ms';
                }
            }
        }

        function updateOverallStatus(data) {
            const overallIndicator = document.querySelector('[data-status="overall"]');
            if (overallIndicator) {
                const dot = overallIndicator.querySelector('.status-dot');
                const text = overallIndicator.querySelector('.status-text');

                if (data.overall === 'healthy') {
                    dot.className = 'status-dot w-4 h-4 bg-green-500 rounded-full';
                } else if (data.overall === 'warning') {
                    dot.className = 'status-dot w-4 h-4 bg-yellow-500 rounded-full';
                } else {
                    dot.className = 'status-dot w-4 h-4 bg-red-500 rounded-full';
                }

                if (text) {
                    text.textContent = data.overall_text;
                }
            }
        }

        // Auto-refresh system status every 30 seconds
        setInterval(checkSystemStatus, 30000);
    </script>

@endsection
