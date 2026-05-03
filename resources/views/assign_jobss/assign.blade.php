@extends('layouts.admin')

@section('title', 'Assign Job')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">

    <!-- Header -->
    <div class="bg-purple-600 text-white px-6 py-4 flex justify-between items-center">
        <h2 class="text-lg font-semibold">Assign Technician – Job #{{ $job->id }}</h2>
        <a href="{{ route('assign_jobs.index') }}" class="text-sm underline hover:text-gray-200">← Back</a>
    </div>

    <!-- Content -->
    <div class="p-6 space-y-6">
        <!-- Job Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <p><span class="font-medium">Customer:</span> {{ $job->customer }}</p>
                <p><span class="font-medium">Phone:</span> {{ $job->phone }}</p>
                <p><span class="font-medium">Address:</span> {{ $job->address }}</p>
                <p><span class="font-medium">Service:</span> {{ $job->service_type }}</p>
                <p><span class="font-medium">Status:</span> 
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $job->status == 'Pending' ? 'bg-yellow-100 text-yellow-700' : 
                           ($job->status == 'In Progress' ? 'bg-blue-100 text-blue-700' : 
                           'bg-green-100 text-green-700') }}">
                        {{ $job->status }}
                    </span>
                </p>
                <p><span class="font-medium">Notes:</span> {{ $job->notes }}</p>
            </div>

            <!-- Map -->
            <div class="h-64 rounded-lg overflow-hidden shadow">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    src="https://www.google.com/maps?q={{ urlencode($job->address) }}&output=embed"
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Assignment Form -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Assign Technician</h3>
            <form method="POST" action="{{ route('assign_jobs.store', $job->id) }}" class="space-y-4">
                @csrf
                <select name="technician_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Select Technician --</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">
                            {{ $tech->name }} (Active Jobs: {{ $tech->active_jobs }})
                        </option>
                    @endforeach
                </select>

                <textarea name="notes" rows="2" placeholder="Add notes (optional)"
                          class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500"></textarea>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('assign_jobs.index') }}" 
                       class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Cancel</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Confirm Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
