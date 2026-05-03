@extends('layouts.staff')

@section('title', 'Edit Customer')

@section('content')
<div class="max-w-4xl mx-auto font-sans text-gray-800">
    <div class="bg-white shadow-md rounded-xl border border-gray-200">

        <!-- Header -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <h1 class="text-xl font-semibold text-gray-800">Edit Customer</h1>
            <p class="text-sm text-gray-500">
                Update customer information.
            </p>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-8">
            @if(session('success'))
                <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('staff.customers.update', $customer->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Info -->
                <section>
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->user->name) }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->user->email) }}" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-semibold">Status *</label>
                            <select id="status" name="status" required
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status', $customer->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Address Info -->
                <section>
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Address Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="address" class="block text-sm font-semibold">Street Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-semibold">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-semibold">Province</label>
                            <input type="text" id="province" name="province" value="{{ old('province', $customer->province) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="zip_code" class="block text-sm font-semibold">ZIP Code</label>
                            <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $customer->zip_code) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </section>

                <!-- Additional Info -->
                <section>
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Additional Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="dob" class="block text-sm font-semibold">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob', $customer->dob) }}"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-semibold">Gender</label>
                            <select id="gender" name="gender"
                                class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" {{ old('gender', $customer->gender) == '' ? 'selected' : '' }}>—</option>
                                <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $customer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('staff.customers.show', $customer->id) }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">
                       Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
