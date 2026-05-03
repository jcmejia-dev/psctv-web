@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Package</h2>
        <a href="{{ route('packages.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('packages.update', $package->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Package Name -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Package Name</label>
            <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('description', $package->description) }}</textarea>
            @error('description') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Speed and Channels -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Speed (Mbps)</label>
                <input type="number" name="speed_mbps" value="{{ old('speed_mbps', $package->speed_mbps) }}"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('speed_mbps') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Channels</label>
                <input type="number" name="channels" value="{{ old('channels', $package->channels) }}"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('channels') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Price -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Price (₱)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $package->price) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            @error('price') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Billing Cycle -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Billing Cycle</label>
            <select name="billing_cycle" required
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="monthly" {{ old('billing_cycle', $package->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="quarterly" {{ old('billing_cycle', $package->billing_cycle) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                <option value="yearly" {{ old('billing_cycle', $package->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            @error('billing_cycle') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="is_active" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ old('is_active', $package->is_active) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $package->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('packages.index') }}" 
               class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
            <button type="submit" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                💾 Update Package
            </button>
        </div>
    </form>
</div>
@endsection
