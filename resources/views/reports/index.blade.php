@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Reports Dashboard</h1>
                    <p class="text-slate-600 mt-1">Comprehensive analytics and performance insights</p>
                </div>
            </div>

            <!-- Date Range Filter & Export Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-4 bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="date" id="fromDate" class="bg-transparent border-none text-sm font-medium text-slate-700 focus:outline-none focus:ring-0" />
                    </div>
                    <span class="text-slate-400">to</span>
                    <div class="flex items-center gap-2">
                        <input type="date" id="toDate" class="bg-transparent border-none text-sm font-medium text-slate-700 focus:outline-none focus:ring-0" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <select id="reportFilter" class="bg-transparent border-none text-sm font-medium text-slate-700 focus:outline-none focus:ring-0">
                            <option value="all">All Reports</option>
                            <option value="revenue">Revenue Only</option>
                            <option value="technician">Technician Only</option>
                            <option value="jobs">Jobs Only</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="btnRefresh" class="group bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Data
                    </button>
                    <button id="btnPrint" class="group bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    <button id="btnPDF" class="group bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Jobs</p>
                        <h2 id="kpiTotalJobs" class="text-3xl font-bold text-slate-900 mt-2">0</h2>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Completed</p>
                        <h2 id="kpiCompleted" class="text-3xl font-bold text-emerald-600 mt-2">0</h2>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pending</p>
                        <h2 id="kpiPending" class="text-3xl font-bold text-amber-600 mt-2">0</h2>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-amber-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Avg Completion</p>
                        <h2 id="kpiAvgCompletion" class="text-3xl font-bold text-indigo-600 mt-2">0 <span class="text-sm font-normal">hrs</span></h2>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Jobs per Day Chart -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-fade-in" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        Jobs per Day
                    </h3>
                </div>
                <div class="h-64">
                    <canvas id="jobsLineChart"></canvas>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-fade-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        Revenue (₱)
                    </h3>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Job Types Distribution -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-fade-in xl:col-span-2" style="animation-delay: 0.7s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                        Job Types Distribution
                    </h3>
                </div>
                <div class="flex justify-center">
                    <div class="w-64 h-64">
                        <canvas id="typeDoughnut"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technician Performance -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.8s">
            <div class="p-6">
                <h3 class="font-semibold text-slate-800 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    Technician Performance
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100/50 text-slate-600 uppercase text-xs">
                            <tr>
                                <th class="p-3 text-left font-semibold rounded-l-xl">Technician</th>
                                <th class="p-3 text-right font-semibold">Jobs Completed</th>
                                <th class="p-3 text-right font-semibold rounded-r-xl">Avg Hours</th>
                            </tr>
                        </thead>
                        <tbody id="techTbody" class="text-slate-700">
                            <tr><td colspan="3" class="p-6 text-center text-slate-500">Loading technician data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.9s">
            <div class="p-6">
                <h3 class="font-semibold text-slate-800 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Recent Service Requests
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100/50 text-slate-600 uppercase text-xs">
                            <tr>
                                <th class="p-3 font-semibold rounded-l-xl">ID</th>
                                <th class="p-3 font-semibold">Customer</th>
                                <th class="p-3 font-semibold">Technician</th>
                                <th class="p-3 font-semibold">Type</th>
                                <th class="p-3 font-semibold">Status</th>
                                <th class="p-3 font-semibold">Assigned</th>
                                <th class="p-3 font-semibold rounded-r-xl">Completed</th>
                            </tr>
                        </thead>
                        <tbody id="jobsTbody" class="text-slate-700">
                            <tr><td colspan="7" class="p-6 text-center text-slate-500">Loading recent jobs...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const from = document.getElementById('fromDate');
    const to = document.getElementById('toDate');
    const btn = document.getElementById('btnRefresh');

    // Default date range (last 30 days)
    const today = new Date();
    const last30 = new Date();
    last30.setDate(today.getDate() - 30);
    from.value = last30.toISOString().split('T')[0];
    to.value = today.toISOString().split('T')[0];

    async function loadReports() {
        const params = new URLSearchParams({
            from: from.value,
            to: to.value,
            filter: document.getElementById('reportFilter').value
        });

        // Show loading states
        document.querySelectorAll('[id^="kpi"]').forEach(el => {
            el.textContent = '...';
        });

        document.getElementById('techTbody').innerHTML = '<tr><td colspan="3" class="p-6 text-center text-slate-500">Loading...</td></tr>';
        document.getElementById('jobsTbody').innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-500">Loading...</td></tr>';

        // Hide sections based on filter
        const filter = document.getElementById('reportFilter').value;
        const jobsChart = document.getElementById('jobsLineChart').parentElement.parentElement;
        const revenueChart = document.getElementById('revenueChart').parentElement.parentElement;
        const typeChart = document.getElementById('typeDoughnut').parentElement.parentElement;
        const techTable = document.getElementById('techTbody').parentElement.parentElement.parentElement;
        const jobsTable = document.getElementById('jobsTbody').parentElement.parentElement.parentElement;

        if (filter === 'revenue') {
            jobsChart.style.display = 'none';
            typeChart.style.display = 'none';
            techTable.style.display = 'none';
            jobsTable.style.display = 'none';
            revenueChart.style.display = 'block';
        } else if (filter === 'technician') {
            jobsChart.style.display = 'none';
            revenueChart.style.display = 'none';
            typeChart.style.display = 'none';
            jobsTable.style.display = 'none';
            techTable.style.display = 'block';
        } else if (filter === 'jobs') {
            revenueChart.style.display = 'none';
            techTable.style.display = 'none';
            jobsChart.style.display = 'block';
            typeChart.style.display = 'block';
            jobsTable.style.display = 'block';
        } else {
            jobsChart.style.display = 'block';
            revenueChart.style.display = 'block';
            typeChart.style.display = 'block';
            techTable.style.display = 'block';
            jobsTable.style.display = 'block';
        }

        try {
            const res = await fetch(`/api/reports/summary?${params}`);
            const data = await res.json();

            // Update KPIs with animation
            animateKPI('kpiTotalJobs', data.summary.total_jobs);
            animateKPI('kpiCompleted', data.summary.completed);
            animateKPI('kpiPending', data.summary.pending);
            animateKPI('kpiAvgCompletion', data.summary.avg_completion);

            // Update progress bars
            updateProgressBars(data.summary);

            // Technician Table
            const techTbody = document.getElementById('techTbody');
            techTbody.innerHTML = data.technicians.length
                ? data.technicians.map(t => `
                    <tr class="border-t border-slate-200 hover:bg-slate-50/50 transition-colors">
                        <td class="p-3 font-medium">${t.name}</td>
                        <td class="p-3 text-right font-semibold text-slate-900">${t.completed}</td>
                        <td class="p-3 text-right font-semibold text-slate-900">${t.avg_hrs}</td>
                    </tr>
                  `).join('')
                : `<tr><td colspan="3" class="p-6 text-center text-slate-500">No technician data available</td></tr>`;

            // Jobs Table
            const jobsTbody = document.getElementById('jobsTbody');
            jobsTbody.innerHTML = data.recent_jobs.length
                ? data.recent_jobs.map(j => `
                    <tr class="border-t border-slate-200 hover:bg-slate-50/50 transition-colors">
                        <td class="p-3 font-mono text-sm font-semibold text-slate-900">#${j.id}</td>
                        <td class="p-3 font-medium">${j.customer}</td>
                        <td class="p-3">${j.technician}</td>
                        <td class="p-3"><span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full text-xs">${j.type}</span></td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold ${
                                j.status === 'Completed' ? 'bg-emerald-100 text-emerald-700' :
                                j.status === 'Pending' ? 'bg-amber-100 text-amber-700' :
                                'bg-blue-100 text-blue-700'
                            }">${j.status}</span>
                        </td>
                        <td class="p-3 text-sm text-slate-600">${j.assigned_at ?? '—'}</td>
                        <td class="p-3 text-sm text-slate-600">${j.completed_at ?? '—'}</td>
                    </tr>
                  `).join('')
                : `<tr><td colspan="7" class="p-6 text-center text-slate-500">No recent jobs found</td></tr>`;

            // Render charts
            if (data.jobs_per_day) renderJobsChart(data.jobs_per_day);
            if (data.revenues) renderRevenueChart(data.revenues);
            if (data.job_types) renderTypeChart(data.job_types);

        } catch (error) {
            console.error('Error loading reports:', error);
            document.getElementById('techTbody').innerHTML = '<tr><td colspan="3" class="p-6 text-center text-rose-500">Error loading data</td></tr>';
            document.getElementById('jobsTbody').innerHTML = '<tr><td colspan="7" class="p-6 text-center text-rose-500">Error loading data</td></tr>';
        }
    }

    function animateKPI(elementId, targetValue) {
        const element = document.getElementById(elementId);
        const current = parseInt(element.textContent) || 0;
        const duration = 1000;
        const steps = 60;
        const stepValue = (targetValue - current) / steps;
        let currentStep = 0;

        const timer = setInterval(() => {
            currentStep++;
            const value = Math.round(current + (stepValue * currentStep));
            element.textContent = value;
            
            if (currentStep >= steps) {
                element.textContent = targetValue;
                clearInterval(timer);
            }
        }, duration / steps);
    }

    function updateProgressBars(summary) {
        const total = summary.total_jobs || 1;
        const completedPercent = (summary.completed / total) * 100;
        const pendingPercent = (summary.pending / total) * 100;
        
        setTimeout(() => {
            document.querySelectorAll('.h-2.bg-blue-600, .h-2.bg-emerald-600, .h-2.bg-amber-600, .h-2.bg-indigo-600').forEach((bar, index) => {
                let width = 0;
                switch(index) {
                    case 0: width = 100; break; // Total jobs - full width
                    case 1: width = completedPercent; break;
                    case 2: width = pendingPercent; break;
                    case 3: width = Math.min((summary.avg_completion / 24) * 100, 100); break; // Assuming 24hrs max for visualization
                }
                bar.style.width = width + '%';
            });
        }, 500);
    }

    // --- Chart functions ---
    let jobsChart, revChart, typeChart;

    function renderJobsChart(data) {
        const ctx = document.getElementById('jobsLineChart').getContext('2d');
        if (jobsChart) jobsChart.destroy();
        jobsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(r => r.date),
                datasets: [{
                    label: 'Jobs per Day',
                    data: data.map(r => r.total),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    function renderRevenueChart(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        if (revChart) revChart.destroy();
        revChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(r => r.date),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: data.map(r => r.total),
                    backgroundColor: 'rgba(79,70,229,0.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    function renderTypeChart(data) {
        const ctx = document.getElementById('typeDoughnut').getContext('2d');
        if (typeChart) typeChart.destroy();
        typeChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(r => r.service_type || 'N/A'),
                datasets: [{
                    data: data.map(r => r.total),
                    backgroundColor: [
                        '#6366F1','#10B981','#F59E0B','#EF4444','#8B5CF6',
                        '#EC4899','#06B6D4','#84CC16','#F97316','#6B7280'
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    }

    btn.addEventListener('click', loadReports);
    
    // Print button handler
    document.getElementById('btnPrint').addEventListener('click', () => {
        const params = new URLSearchParams({
            from: from.value,
            to: to.value,
            filter: document.getElementById('reportFilter').value
        });
        window.location.href = `/reports/print?${params}`;
    });

    // PDF button handler
    document.getElementById('btnPDF').addEventListener('click', () => {
        const params = new URLSearchParams({
            from: from.value,
            to: to.value,
            filter: document.getElementById('reportFilter').value
        });
        window.location.href = `/reports/pdf?${params}`;
    });
    
    loadReports(); // initial load
});
</script>
@endsection