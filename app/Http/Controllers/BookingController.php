<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Fetch all bookings
        // $bookings = Booking::all();
        $bookings = Booking::with(['parkingSpots.authOwner', 'parkingSpots.photos', 'user', 'cancelledBooking'])->get();

        // Return the bookings data
        return response()->json($bookings, 200);
    }

    public function ownerindex()
    {
        // Retrieve the authenticated owner
        $owner = Auth::guard('owner')->user();

        // Check if the owner is authenticated
        if ($owner) {
            // Fetch booking history associated with the authenticated owner
            $bookings = Booking::whereHas('parkingSpots', function ($query) use ($owner) {
                $query->where('auth_owner_id', $owner->id);
            })
                ->with(['parkingSpots.authOwner', 'parkingSpots.photos', 'user', 'cancelledBooking'])
                ->get();

            // Return the booking history data
            return response()->json($bookings, 200);
        } else {
            // If owner is not authenticated, return an unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'from_datetime' => 'required|date',
            // 'to_datetime' => 'required|date|after:from_datetime',
            'to_datetime' => 'required|date',
            'vehicle_name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'slot' => 'required|string|max:255',
            'amount_paid' => 'required|numeric',
            'booked_on' => 'required|date',
            'total_hours' => 'required|integer',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Fetch the currently authenticated user
        // Check if the user is authenticated
        if (! $user = Auth::guard('web')->user()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        // Create the booking with the user ID
        $booking = $user->bookings()->create($request->all());

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Define validation rules
    //     $rules = [
    //         'parking_spot_id' => 'string',
    //         'from_datetime' => 'required|date',
    //         'to_datetime' => 'required|date|after:from_datetime',
    //         'vehicle_name' => 'required|string|max:255',
    //         'vehicle_number' => 'required|string|max:255',
    //         'slot' => 'required|string|max:255',
    //         'amount_paid' => 'required|numeric',
    //         'booked_on' => 'required|date',
    //         'total_hours' => 'required|integer',
    //         'location' => 'required|string|max:255',
    //         'status' => 'required|string|max:255',

    //     ];

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), $rules);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     // Get the authenticated user's ID
    //     $user = Auth::guard('web')->user();

    //     // Add user_id to the request data
    //     $data = $request->all();

    //     $data['user_id'] = $user->id;

    //     // Create a new Booking record
    //     $booking = Booking::create($data);

    //     return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        // Validate incoming requests if necessary
        $validatedData = $request->validate([
            'name' => 'required|string',
            'from_datetime' => 'required|date',
            'to_datetime' => 'required|date',
            'vehicle_name' => 'required|string',
            'vehicle_number' => 'required|string',
            'slot' => 'required|string',
            'amount_paid' => 'required|numeric',
            'booked_on' => 'required|date',
            'total_hours' => 'required|integer',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Find the booking record by ID
        $booking = Booking::findOrFail($id);

        // Update the booking record with the validated data
        $booking->update($validatedData);

        // Optionally, you can return a response indicating success or failure
        return response()->json(['message' => 'Booking updated successfully'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
