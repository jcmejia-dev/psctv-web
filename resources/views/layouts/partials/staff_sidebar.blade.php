<aside id="sidebar"
    class="bg-gray-900 text-gray-200 h-screen w-64 p-5 fixed top-0 left-0 transition-all duration-300 ease-in-out overflow-y-auto shadow-lg z-50">

    <!-- Logo -->
    <div class="flex items-center justify-center mb-6 border-b border-gray-700 pb-3">
        <img src="{{ asset('images/logo3.png') }}" alt="PCTVS Logo" class="h-14 w-auto">
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="space-y-2 text-sm">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.dashboard') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-home class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- Dynamic Modules Based on Access -->
            @php
                $modules = \App\Models\StaffModule::where('is_active', true)->where('key', '!=', 'dashboard')->orderBy('order')->get();
            @endphp

            @foreach($modules as $module)
                @if(auth()->user()->hasModuleAccess($module->key))
                    <li>
                        <a href="{{ route($module->route_prefix . '.index') }}"
                           class="flex items-center py-2 px-3 rounded-md transition
                           {{ request()->routeIs($module->route_prefix . '.index') || request()->routeIs($module->route_prefix . '.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                            @if($module->icon === 'heroicon-o-wrench-screwdriver')
                                <x-heroicon-o-wrench-screwdriver class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-users')
                                <x-heroicon-o-users class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-user-group')
                                <x-heroicon-o-user-group class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-cube')
                                <x-heroicon-o-cube class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-currency-dollar')
                                <x-heroicon-o-currency-dollar class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-document-text')
                                <x-heroicon-o-document-text class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-megaphone')
                                <x-heroicon-o-megaphone class="h-5 w-5 mr-3"/>
                            @elseif($module->icon === 'heroicon-o-chart-bar')
                                <x-heroicon-o-chart-bar class="h-5 w-5 mr-3"/>
                            @endif
                            <span class="sidebar-text transition-opacity duration-300">{{ $module->name }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>
    </nav>

</aside>
