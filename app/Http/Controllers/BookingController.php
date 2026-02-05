<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CleaningJob;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    public function calculator()
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('booking.calculator', compact('services'));
    }

    public function calculate(Request $request)
    {
        $service = Service::find($request->service_id);

        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $bedrooms = (int) $request->bedrooms;
        $bathrooms = (int) $request->bathrooms;
        $sqft = (int) $request->square_feet;

        $price = $service->calculatePrice($bedrooms, $bathrooms, $sqft);
        $duration = $service->formattedDuration();

        return response()->json([
            'price' => $price,
            'formatted_price' => '$' . number_format($price, 2),
            'duration' => $duration,
            'service_name' => $service->name,
        ]);
    }

    public function book(Request $request)
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $preselected = [
            'service_id' => $request->service_id,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'square_feet' => $request->square_feet,
            'price' => $request->price,
        ];

        return view('booking.form', compact('services', 'preselected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string|size:2',
            'zip' => 'required|string|max:10',
            'bedrooms' => 'required|integer|min:1|max:10',
            'bathrooms' => 'required|integer|min:1|max:10',
            'square_feet' => 'nullable|integer|min:0',
            'service_id' => 'required|exists:services,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required',
            'has_pets' => 'boolean',
            'pet_details' => 'nullable|string|max:500',
            'access_instructions' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip' => $validated['zip'],
                'bedrooms' => $validated['bedrooms'],
                'bathrooms' => $validated['bathrooms'],
                'square_feet' => $validated['square_feet'],
                'has_pets' => $validated['has_pets'] ?? false,
                'pet_details' => $validated['pet_details'],
                'access_instructions' => $validated['access_instructions'],
                'password' => Hash::make(substr(md5(time()), 0, 8)), // Random password
            ]
        );

        // Update customer details if they exist
        $customer->update([
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'square_feet' => $validated['square_feet'],
            'has_pets' => $validated['has_pets'] ?? false,
            'pet_details' => $validated['pet_details'],
            'access_instructions' => $validated['access_instructions'],
        ]);

        // Calculate price
        $service = Service::find($validated['service_id']);
        $price = $service->calculatePrice(
            $validated['bedrooms'],
            $validated['bathrooms'],
            $validated['square_feet'] ?? 0
        );

        // Create the job
        $job = CleaningJob::create([
            'customer_id' => $customer->id,
            'service_id' => $validated['service_id'],
            'scheduled_date' => $validated['preferred_date'],
            'scheduled_time' => $validated['preferred_time'],
            'estimated_duration' => $service->estimated_minutes,
            'quoted_price' => $price,
            'status' => 'scheduled',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('booking.confirmation', $job)
            ->with('success', 'Booking confirmed!');
    }

    public function confirmation(CleaningJob $job)
    {
        $job->load(['customer', 'service']);

        return view('booking.confirmation', compact('job'));
    }
}
