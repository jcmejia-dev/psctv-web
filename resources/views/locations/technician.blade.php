@extends('layouts.admin')

@section('title', 'Technician Location')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-lg max-w-4xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Technician Location</h2>

    <p><strong>Name:</strong> {{ $technician->full_name }}</p>
    <p><strong>Email:</strong> {{ $technician->email }}</p>
    <p><strong>Phone:</strong> {{ $technician->phone }}</p>
    <p><strong>Address:</strong> {{ $technician->address }}</p>

    @if($technician->latitude && $technician->longitude)
    <div class="mt-6">
        <iframe 
            width="100%" 
            height="400" 
            frameborder="0" 
            scrolling="no" 
            marginheight="0" 
            marginwidth="0"
            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $technician->longitude-0.01 }},{{ $technician->latitude-0.01 }},{{ $technician->longitude+0.01 }},{{ $technician->latitude+0.01 }}&layer=mapnik&marker={{ $technician->latitude }},{{ $technician->longitude }}">
        </iframe>
        <br/>
        <small>
            <a href="https://www.openstreetmap.org/?mlat={{ $technician->latitude }}&mlon={{ $technician->longitude }}#map=18/{{ $technician->latitude }}/{{ $technician->longitude }}" target="_blank">View Larger Map</a>
        </small>
    </div>
    @else
    <p class="text-gray-500 mt-4">Location not available for this technician.</p>
    @endif

</div>
@endsection
