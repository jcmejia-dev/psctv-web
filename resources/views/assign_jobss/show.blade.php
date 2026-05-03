@extends('layouts.admin')

@section('title', 'Job Details')

@section('content')
<div class="p-8 bg-white rounded-2xl shadow-lg max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Job #REQ-1001</h1>
            <p class="text-sm text-gray-500">Installation Request</p>
        </div>
        <a href="{{ route('assign_jobs.index') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm shadow">
           ← Back to Jobs
        </a>
    </div>

    <!-- Job Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
        <div class="space-y-2">
            <p><strong>👤 Customer:</strong> Maria Santos</p>
            <p><strong>📞 Phone:</strong> 09123456789</p>
            <p><strong>✉️ Email:</strong> maria@example.com</p>
            <p><strong>📍 Address:</strong> Barangay 2, Panabo City</p>
        </div>
        <div class="space-y-2">
            <p>
                <strong>Status:</strong> 
                <span id="status-badge" class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">
                    Pending
                </span>
            </p>
            <p><strong>Requested At:</strong> Sept 11, 2025 - 09:00 AM</p>
            <p><strong>Notes:</strong> Customer requested installation of cable + internet.</p>
        </div>
    </div>

    <!-- Assign Technician -->
    <div class="border-t pt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Assign Technician</h2>
        <form method="POST" action="{{ route('assign_jobs.assign', 1001) }}" class="flex gap-3 items-center">
            @csrf
            <select id="technician-select" name="technician_id" 
                    class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300">
                <option value="">-- Select Technician --</option>
                <option value="1">John Doe - 📞 0911222333</option>
                <option value="2">Jane Smith - 📞 0999888777</option>
            </select>
            <button id="assign-btn" type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Save
            </button>
        </form>
        <p id="assign-warning" class="hidden text-red-500 text-xs mt-2">
            ⚠ Cannot assign technician to a completed or cancelled job.
        </p>
    </div>

    <!-- Update Status -->
    <div class="border-t pt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Update Job Status</h2>
        <form method="POST" action="{{ route('assign_jobs.updateStatus', 1001) }}" class="flex gap-3 items-center">
            @csrf
            <select name="status" id="status-select" 
                    class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-300">
                <option value="Pending">Pending</option>
                <option value="Assigned">Assigned</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Verified">Verified</option>
            </select>
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Update
            </button>
        </form>
    </div>

    <!-- Job History -->
    <div class="border-t pt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Job History</h2>
        <ul class="list-disc pl-6 text-gray-600 text-sm space-y-1">
            <li>Created: Sept 10, 2025 - 9:00 AM</li>
            <li>Status changed to Pending</li>
        </ul>
    </div>
</div>

<!-- Realtime badge update -->
<script>
document.getElementById('status-select').addEventListener('change', function() {
    let newStatus = this.value;
    let badge = document.getElementById('status-badge');
    let classes = {
        "Pending": "bg-yellow-100 text-yellow-700",
        "Assigned": "bg-purple-100 text-purple-700",
        "In Progress": "bg-blue-100 text-blue-700",
        "Completed": "bg-green-100 text-green-700",
        "Verified": "bg-teal-100 text-teal-700",
        "Cancelled": "bg-gray-200 text-gray-700"
    };
    badge.textContent = newStatus;
    badge.className = classes[newStatus] + " px-2 py-1 rounded-full text-xs";

    let technicianSelect = document.getElementById('technician-select');
    let assignBtn = document.getElementById('assign-btn');
    let warning = document.getElementById('assign-warning');

    if (newStatus === "Completed" || newStatus === "Cancelled") {
        technicianSelect.disabled = true;
        assignBtn.disabled = true;
        assignBtn.classList.add("opacity-50", "cursor-not-allowed");
        warning.classList.remove("hidden");
    } else {
        technicianSelect.disabled = false;
        assignBtn.disabled = false;
        assignBtn.classList.remove("opacity-50", "cursor-not-allowed");
        warning.classList.add("hidden");
    }
});
</script>
@endsection
