<header id="mainHeader" class="fixed top-0 right-0 z-40 flex items-center bg-white shadow-md px-6 py-3 border-b border-gray-200 transition-all duration-300" style="left: 256px;">

    <!-- Sidebar Toggle -->
    <button onclick="toggleSidebar()" 
        class="flex items-center justify-center w-10 h-10 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-200"
        id="sidebarToggle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <h1 class="text-2xl font-semibold text-gray-800 ml-4">@yield('title')</h1>

    <div class="ml-auto flex items-center space-x-6">

        <!-- 🔍 Global Search -->
        <div
            x-data="{
                open: false,
                query: '',
                results: [],
                activeIndex: 0,
                loading: false,
                isOnline: navigator.onLine,

                init() {
                    window.addEventListener('online', () => this.isOnline = true);
                    window.addEventListener('offline', () => this.isOnline = false);
                },

                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.open = false;
                        this.loading = false;
                        return;
                    }

                    if (!this.isOnline) {
                        this.results = [];
                        this.open = false;
                        this.loading = false;
                        return;
                    }

                    this.loading = true;
                    try {
                        const res = await fetch(`/staff/search?q=${this.query}`);
                        this.results = await res.json();
                        this.open = true;
                        this.activeIndex = 0;
                    } catch (error) {
                        console.error('Search error:', error);
                        this.results = [];
                        this.open = false;
                    } finally {
                        this.loading = false;
                    }
                },

                navigate(direction) {
                    if (!this.open || this.results.length === 0) return;
                    const max = this.results.length - 1;
                    if (direction === 'up') {
                        this.activeIndex = this.activeIndex > 0 ? this.activeIndex - 1 : max;
                    } else {
                        this.activeIndex = this.activeIndex < max ? this.activeIndex + 1 : 0;
                    }
                },

                selectActive() {
                    if (this.results[this.activeIndex]) {
                        window.location.href = this.results[this.activeIndex].url;
                    }
                }
            }"
            class="relative hidden sm:block"
            x-init="init()"
        >
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search customers, technicians, or requests..."
                    x-model="query"
                    @input.debounce.400ms="search()"
                    @keydown.arrow-down.prevent="navigate('down')"
                    @keydown.arrow-up.prevent="navigate('up')"
                    @keydown.enter.prevent="selectActive()"
                    @click.away="open = false"
                    class="pl-12 pr-12 py-3 w-80 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none focus:border-blue-500 transition-all duration-300 shadow-sm hover:shadow-md"
                />

                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </div>

                <!-- Loading Spinner -->
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Dropdown Results -->
            <div
                x-show="open && results.length > 0"
                x-transition
                class="absolute top-full left-0 mt-3 w-full bg-white border border-gray-200 rounded-xl shadow-xl z-50 max-h-64 overflow-y-auto"
            >
                <template x-for="(item, index) in results" :key="item.type + item.id">
                    <a :href="item.url"
                       :class="{
                           'bg-blue-50 text-blue-700': index === activeIndex,
                           'hover:bg-gray-50': index !== activeIndex
                       }"
                       class="flex items-center px-4 py-3 text-sm text-gray-700 transition cursor-pointer rounded-lg mx-1"
                    >
                        <span x-text="item.icon" class="mr-3 text-lg"></span>
                        <div class="flex-1">
                            <span class="font-semibold" x-text="item.name"></span>
                            <p class="text-xs text-gray-500" x-text="item.type"></p>
                        </div>
                    </a>
                </template>
            </div>

            <!-- Empty state -->
            <div
                x-show="open && results.length === 0 && query.length > 1"
                class="absolute top-full left-0 mt-3 w-full bg-white border border-gray-200 rounded-xl shadow-xl p-4 text-gray-500 text-sm"
            >
                No results found.
            </div>
        </div>

        <!-- 🔔 Staff Notifications -->
        @php
            $user = Auth::user();
            $notifications = $user ? $user->unreadNotifications()->take(5)->get() : collect();
            $totalUnreadCount = $user ? $user->unreadNotifications()->count() : 0;
            $displayCount = $totalUnreadCount > 5 ? '99+' : $totalUnreadCount;
        @endphp
        <div class="relative" x-data="{
            open: false,
            markAsRead() {
                // Immediately hide the notification count
                const countElement = document.getElementById('notifCount');
                if (countElement) {
                    countElement.classList.add('hidden');
                }

                // Make AJAX call to mark as read on server
                fetch('{{ route('staff.notifications.markAllRead') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        // Update notification items styling
                        const notifList = document.getElementById('notifList');
                        if (notifList) {
                            const unreadItems = notifList.querySelectorAll('.bg-blue-50');
                            unreadItems.forEach(item => {
                                item.classList.remove('bg-blue-50', 'shadow-sm', 'border', 'border-blue-100');
                            });
                        }
                    }
                }).catch(error => {
                    console.error('Error marking notifications as read:', error);
                });
            }
        }" @click.away="open = false">
            <button @click="open = !open; if (open) markAsRead()" class="relative text-gray-600 hover:text-gray-800 transition duration-200" id="notifBell">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span id="notifCount"
                      class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1 {{ $totalUnreadCount > 0 ? '' : 'hidden' }}">
                    {{ $displayCount }}
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition.opacity
                class="absolute right-0 mt-3 w-80 bg-white shadow-lg rounded-md border z-50 overflow-hidden transition-all duration-200">
                
                <div class="p-3 border-b font-semibold text-gray-700 flex justify-between items-center">
                    <span>Notifications</span>
                    <a href="{{ route('staff.notifications.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>

                <ul id="notifList" class="max-h-64 overflow-y-auto">
                    @forelse($notifications as $note)
                        @php
                            $isRead = !is_null($note->read_at);
                            $type = class_basename($note->type);
                            $icon = match($type) {
                                'NewAnnouncementNotification' => ['📢', 'text-orange-600'],
                                'TechnicianAssigned' => ['🧰', 'text-blue-600'],
                                'ServiceCompleted' => ['✅', 'text-green-600'],
                                default => ['🔔', 'text-gray-600'],
                            };
                        @endphp
                        <li class="px-4 py-3 hover:bg-gray-50 border-b {{ $isRead ? '' : 'bg-blue-50 shadow-sm border border-blue-100' }}">
                            <p class="text-sm font-semibold text-gray-800">{{ $icon[0] }} 
                                @switch($type)
                                    @case('NewAnnouncementNotification')
                                        New Announcement: {{ $note->data['title'] ?? 'Untitled' }}
                                        @break
                                    @case('TechnicianAssigned')
                                        Service Request Assigned
                                        @break
                                    @case('ServiceCompleted')
                                        Service Request Completed
                                        @break
                                    @default
                                        {{ $note->data['message'] ?? 'Notification' }}
                                @endswitch
                            </p>
                            @if(!empty($note->data['message']))
                                <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($note->data['message']), 120) }}</p>
                            @endif
                            <span class="block text-[10px] text-gray-400 mt-1">{{ $note->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-sm text-gray-500 text-center">No new notifications</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- 👤 User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center space-x-3 bg-white px-4 py-2 rounded-full shadow hover:shadow-lg border transition duration-200">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                     alt="Avatar" class="w-9 h-9 rounded-full" />
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200"
                     :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition.opacity
                 class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                <a href="{{ route('staff.profile.show') }}"
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">👤 Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

</header>
</header>