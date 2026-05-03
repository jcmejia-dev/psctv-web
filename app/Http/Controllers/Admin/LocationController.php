<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Show Technician Location
    public function technician($id)
    {
        $technician = Technician::findOrFail($id);
        return view('locations.technician', compact('technician'));
    }

    // Show Customer Location
    public function customer($id)
    {
        $customer = Customer::findOrFail($id);
        return view('locations.customer', compact('customer'));
    }

    // Show Route from Technician to Customer
    public function route(ServiceRequest $request)
{
    $technician = $request->technician;

    // Make sure both locations exist
    if (!$technician || !$technician->latitude || !$technician->longitude) {
        abort(404, "Technician location not available");
    }

    return view('locations.route', [
        'serviceRequest' => $request,
        'technicianLat'  => $technician->latitude,
        'technicianLon'  => $technician->longitude,
        'customerLat'    => $request->latitude,
        'customerLon'    => $request->longitude,
    ]);
}


    
public function serviceRequestMap($id)
{
    $serviceRequest = ServiceRequest::with('technician')->findOrFail($id);

    return view('locations.service_request', compact('serviceRequest'));

    
}

public function assignPage(ServiceRequest $request)
{
    $technicians = Technician::available()->get(); // scopeAvailable from your model
    return view('service_requests.assign_and_map', compact('request', 'technicians'));
}



}
