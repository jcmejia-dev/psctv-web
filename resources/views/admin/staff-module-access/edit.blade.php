@extends('layouts.admin')

@section('title', 'Manage Staff Module Access - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6" x-data="moduleAccessManager()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-slate-900">Module Access Control</h1>
                    </div>
                    <p class="text-slate-600 mt-2">Configure which modules <strong class="text-blue-700">{{ $user->name }}</strong> can access</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('users.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 shadow-sm border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Quick Actions -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Search modules by name or description..."
                           class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-2">
                <button @click="enableAllModules()" type="button" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enable All
                </button>
                <button @click="disableAllModules()" type="button" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Disable All
                </button>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('staff-module-access.update', $user->id) }}" method="POST" class="space-y-6" @submit.prevent="submitForm">
            @csrf
            @method('PUT')

            <!-- Modules Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
                <template x-for="module in filteredModules" :key="module.id">
                    <div class="module-card bg-white rounded-xl border border-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden"
                         :class="module.enabled ? 'ring-2 ring-blue-500/20 shadow-md' : ''">

                        <!-- Module Header -->
                        <div class="p-4">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm border"
                                     :class="getModuleColor(module.name).bg">
                                    <div x-html="getModuleIcon(module.name)" class="w-5 h-5"></div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-slate-900 text-sm leading-tight truncate" x-text="module.name" title="module.name"></h3>
                                            <p class="text-xs text-slate-600 mt-1 line-clamp-2 leading-relaxed" x-text="module.description"></p>
                                            <div class="flex items-center gap-1 mt-2">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 truncate max-w-[120px]"
                                                      :title="module.route_prefix || 'N/A'">
                                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                    </svg>
                                                    <span x-text="module.route_prefix || 'N/A'" class="truncate"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Toggle Switch -->
                                        <div class="flex-shrink-0">
                                            <label class="relative inline-flex items-center cursor-pointer group">
                                                <input type="checkbox"
                                                       :name="'module_access[]'"
                                                       :value="module.id"
                                                       :checked="module.enabled"
                                                       @change="updateAccess(module.id, $event.target.checked)"
                                                       class="sr-only peer">
                                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-blue-500 group-hover:shadow-md border border-transparent peer-checked:border-blue-400"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Status and Date -->
                                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                        <span class="text-xs font-medium transition-colors"
                                              :class="module.enabled ? 'text-green-600' : 'text-slate-500'">
                                            <span x-show="!module.enabled">Disabled</span>
                                            <span x-show="module.enabled" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Enabled
                                            </span>
                                        </span>
                                        <span class="text-xs text-slate-500" x-text="module.updated_at ? new Date(module.updated_at).toLocaleDateString() : 'N/A'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Billing Permissions Section -->
            <div x-show="isBillingEnabled()" class="mt-8 bg-gradient-to-r from-lime-50 to-emerald-50 border border-lime-200 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-lime-100 rounded-xl">
                        <svg class="w-6 h-6 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Billing Permissions</h3>
                        <p class="text-sm text-slate-600">Configure specific permissions for billing operations</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($billingPermissions as $permission)
                    <div class="bg-white rounded-xl border border-slate-200 p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="billing_permissions[]"
                                           value="{{ $permission->id }}"
                                           {{ isset($userBillingPermissions[$permission->id]) && $userBillingPermissions[$permission->id] ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-5 h-5 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-lime-300 rounded peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-lime-500"></div>
                                </label>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-slate-900 text-sm">{{ ucwords(str_replace('_', ' ', $permission->action)) }}</h4>
                                <p class="text-xs text-slate-600 mt-1">{{ $permission->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Billing Permission Rules:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li><strong>View Payments:</strong> Staff MUST see all payments for verification and customer service</li>
                                <li><strong>Record Payment:</strong> Staff can mark invoices as paid and enter payment details</li>
                                <li><strong>Print Receipt:</strong> Staff can generate receipts for walk-in customers</li>
                                <li><strong>Send Receipt Email:</strong> Staff can trigger sending receipts to customer emails</li>
                                <li><strong>View Invoice Details:</strong> Staff can view invoice amounts, previous payments, and subscription details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                
                <!-- No Results -->
                <div x-show="filteredModules.length === 0 && searchQuery" 
                     class="col-span-full text-center py-12">
                    <div class="p-4 bg-slate-50 rounded-2xl max-w-md mx-auto">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-700 mb-2">No modules found</h3>
                        <p class="text-slate-500">Try adjusting your search terms to find what you're looking for.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200 bg-white -mx-6 px-6 py-4 rounded-b-lg sticky bottom-0 shadow-lg">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Module Access
                </button>
                <a href="{{ route('users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-semibold transition-all duration-200 text-center">
                    Cancel
                </a>
                
                <!-- Unsaved Changes Indicator -->
                <div x-show="hasUnsavedChanges" class="flex items-center gap-2 text-amber-600 ml-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-medium">Unsaved changes</span>
                </div>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
            <div class="flex gap-4">
                <div class="p-2 bg-blue-100 rounded-xl flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-2 text-blue-900">How it works:</p>
                    <ul class="list-none space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Toggle switches to enable/disable module access for <strong>{{ $user->name }}</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Use search to quickly find specific modules by name or description</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Progress bar shows current access status at a glance</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Disabled modules won't appear in the staff navigation sidebar</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function moduleAccessManager() {
    return {
        searchQuery: '',
        hasUnsavedChanges: false,
        modules: [
            @foreach($modules as $module)
            {
                id: {{ $module->id }},
                name: '{{ $module->name }}',
                description: '{{ $module->description }}',
                route_prefix: '{{ $module->route_prefix }}',
                updated_at: '{{ $module->updated_at }}',
                enabled: {{ isset($userAccess[$module->id]) && $userAccess[$module->id] ? 'true' : 'false' }}
            },
            @endforeach
        ],
        enabledCount: {{ count(array_filter($userAccess ?? [])) }},
        
        get filteredModules() {
            if (!this.searchQuery) return this.modules;
            const query = this.searchQuery.toLowerCase();
            return this.modules.filter(module => 
                module.name.toLowerCase().includes(query) || 
                module.description.toLowerCase().includes(query)
            );
        },
        

        
        updateAccess(moduleId, isChecked) {
            const module = this.modules.find(m => m.id === moduleId);
            if (module) {
                const wasEnabled = module.enabled;
                module.enabled = isChecked;
                
                if (wasEnabled !== isChecked) {
                    this.enabledCount += isChecked ? 1 : -1;
                    this.hasUnsavedChanges = true;
                }
            }
        },
        
        enableAllModules() {
            if (confirm('Are you sure you want to enable all modules?')) {
                this.modules.forEach(module => {
                    if (!module.enabled) {
                        module.enabled = true;
                        this.enabledCount++;
                    }
                });
                this.hasUnsavedChanges = true;
            }
        },
        
        disableAllModules() {
            if (confirm('Are you sure you want to disable all modules?')) {
                this.modules.forEach(module => {
                    if (module.enabled) {
                        module.enabled = false;
                        this.enabledCount--;
                    }
                });
                this.hasUnsavedChanges = true;
            }
        },
        
        submitForm() {
            if (confirm('Are you sure you want to save these changes?')) {
                this.hasUnsavedChanges = false;
                this.$el.submit();
            }
        },
        
        getModuleIcon(moduleName) {
            const icons = {
                'Dashboard': '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'Users': '<svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>',
                'Reports': '<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                'Packages': '<svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                'Subscriptions': '<svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"/></svg>',
                'Service Requests': '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 105.25 5.25A9.75 9.75 0 0012 2.25z"/></svg>',
                'Technicians': '<svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>',
                'Customers': '<svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                'Notifications': '<svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21V9m0 0a3 3 0 110-6 3 3 0 010 6z"/></svg>',
                'Billing': '<svg class="w-6 h-6 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
            };

            return icons[moduleName] || '<svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        },
        
        getModuleColor(moduleName) {
            const colors = {
                'Dashboard': { bg: 'bg-blue-100', border: 'border-blue-200' },
                'Users': { bg: 'bg-purple-100', border: 'border-purple-200' },
                'Reports': { bg: 'bg-amber-100', border: 'border-amber-200' },
                'Packages': { bg: 'bg-emerald-100', border: 'border-emerald-200' },
                'Subscriptions': { bg: 'bg-cyan-100', border: 'border-cyan-200' },
                'Service Requests': { bg: 'bg-red-100', border: 'border-red-200' },
                'Technicians': { bg: 'bg-indigo-100', border: 'border-indigo-200' },
                'Customers': { bg: 'bg-pink-100', border: 'border-pink-200' },
                'Notifications': { bg: 'bg-orange-100', border: 'border-orange-200' },
                'Billing': { bg: 'bg-lime-100', border: 'border-lime-200' }
            };
            
            return colors[moduleName] || { bg: 'bg-slate-100', border: 'border-slate-200' };
        }
    };
}
</script>
@endpush
@endsection