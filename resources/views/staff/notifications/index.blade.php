@extends('layouts.staff')
@section('title', 'Notifications')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-lg space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b pb-4 gap-3">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <x-lucide-bell class="w-6 h-6 text-blue-600" />
            Notifications
            @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">
                    {{ Auth::user()->unreadNotifications->count() }} new
                </span>
            @endif
        </h2>

        @if(Auth::user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('staff.notifications.markAllRead') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 text-sm font-semibold rounded-lg hover:bg-blue-700 shadow-sm transition">
                <x-lucide-check class="w-4 h-4"/> Mark all as read
            </button>
        </form>
        @endif
    </div>

    <!-- Notification List -->
    <ul class="divide-y divide-gray-100">
        @forelse($notifications as $note)
            @php
                $isRead = !is_null($note->read_at);
                $type = class_basename($note->type);

                // Pick icon and color per type
                $icon = match($type) {
                    'NewAnnouncementNotification' => ['📢', 'text-orange-600'],
                    'ServiceAssigned' => ['🧰', 'text-blue-600'],
                    'ServiceCompleted' => ['✅', 'text-green-600'],
                    default => ['🔔', 'text-gray-600'],
                };
            @endphp

            <li class="p-4 rounded-xl mb-2 transition hover:bg-gray-50 
                       {{ $isRead ? 'bg-gray-50' : 'bg-blue-50 border border-blue-100 shadow-sm' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg {{ $icon[1] }}">{{ $icon[0] }}</span>
                            <p class="font-semibold text-gray-800">
                                @switch($type)
                                    @case('NewAnnouncementNotification')
                                        New Announcement: {{ $note->data['title'] ?? 'Untitled' }}
                                        @break
                                    @case('ServiceAssigned')
                                        Service Assigned
                                        @break
                                    @case('ServiceCompleted')
                                        Service Completed
                                        @break
                                    @default
                                        {{ $note->data['message'] ?? 'Notification' }}
                                @endswitch
                            </p>
                        </div>

                        @if(!empty($note->data['message']))
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ Str::limit(strip_tags($note->data['message']), 120) }}
                            </p>
                        @endif

                        @if(!empty($note->data['customer_name']))
                            <p class="text-xs text-gray-500 mt-1">
                                👤 Customer: {{ $note->data['customer_name'] }}
                            </p>
                        @endif

                        @if(!empty($note->data['service_type']))
                            <p class="text-xs text-gray-500">
                                🛠 Service Type: {{ $note->data['service_type'] }}
                            </p>
                        @endif

                        @if(!empty($note->data['service_request_id']))
                            <a href="{{ route('staff.service_requests.show', $note->data['service_request_id']) }}" 
                               class="inline-block text-xs text-blue-600 font-semibold hover:underline mt-2">
                                View Request →
                            </a>
                        @endif
                    </div>

                    <div class="text-right ml-4">
                        <p class="text-xs text-gray-400 whitespace-nowrap">
                            {{ $note->created_at->diffForHumans() }}
                        </p>

                        @if(!$isRead)
                            <form method="POST" action="{{ route('staff.notifications.markRead', $note->id) }}" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="text-xs text-blue-600 hover:underline font-medium">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </li>
        @empty
            <li class="text-center text-gray-500 py-8 italic">No notifications yet.</li>
        @endforelse
    </ul>

    <!-- Pagination -->
    <div class="pt-4 border-t">
        {{ $notifications->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection
