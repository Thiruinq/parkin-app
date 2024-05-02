<?php

namespace App\Http\Controllers;

use App\Models\AuthOwner;
use App\Models\ParkingSpots;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingSpotsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ownerindex()
    {
        // Retrieve the authenticated user
        $user = Auth::guard('owner')->user();

        // Check if the user is authenticated
        if ($user) {
            // Fetch parking spots associated with the authenticated user
            $parkingSpots = $user->parkingSpots()->with(['photos', 'authOwner'])->get();

            // Return the data as JSON response
            return response()->json($parkingSpots);
        } else {
            // If user is not authenticated, return an unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function index()
    {
        // Fetch all parking spots
        $parkingSpots = ParkingSpots::with(['photos', 'authOwner'])->get();

        // Return the data as JSON response
        return response()->json($parkingSpots);
    }

    public function getParkingSpots()
    {
        // $parkingSpots = ParkingSpots::all();
        $parkingSpots = ParkingSpots::with(['photos', 'authOwner'])->get();

        return response()->json($parkingSpots);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     //
    //     $request->validate([
    //         'slot_name' => 'required|string|max:255',
    //         'available_time' => 'required|string',
    //         'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
    //         'google_map' => 'required|string',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'available_slots' => 'required|integer',
    //         'from_date_time' => 'required|string',
    //         'to_date_time' => 'required|string',
    //         'nearby_places' => 'required|string',
    //         'vehicle_types' => 'required|string',
    //         'vehicle_fees' => 'required|string',
    //     ]);

    //     $slot_created = ParkingSpots::create($request->all());

    //     return $slot_created;

    // }

    // public function create(Request $request)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'slot_name' => 'required|string|max:255',
    //         'available_time' => 'required|string',
    //         'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
    //         'google_map' => 'required|string',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'available_slots' => 'required|integer',
    //         'from_date_time' => 'required|string',
    //         'to_date_time' => 'required|string',
    //         'nearby_places' => 'required|string',
    //         'vehicle_types' => 'required|string',
    //         'vehicle_fees' => 'required|string',
    //     ]);

    //     // Create the parking spot
    //     $parkingSpot = ParkingSpots::create($request->except('photos'));

    //     // Save photos
    //     if ($request->hasFile('photos')) {
    //         foreach ($request->file('photos') as $photo) {
    //             $path = $photo->store('public/photos'); // Adjust the storage path as needed
    //             $parkingSpot->photos()->create(['photo_path' => $path]);
    //         }
    //     }

    //     return $parkingSpot;
    // }

    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'slot_name' => 'required|string|max:255',
            'available_time' => 'required|string',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            'google_map' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'available_slots' => 'required|integer',
            'from_date_time' => 'required|string',
            'to_date_time' => 'required|string',
            'nearby_places' => 'required|string',
            'vehicle_types' => 'required|string',
            'vehicle_fees' => 'required|string',
        ]);

        // Fetch the currently authenticated user using the AuthOwner model
        $user = Auth::guard('owner')->user();

        // Create the parking spot with the user ID
        $parkingSpot = $user->parkingSpots()->create($request->except('photos'));

        // Save photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public/photos'); // Adjust the storage path as needed
                $parkingSpot->photos()->create(['photo_path' => $path]);
            }
        }

        return $parkingSpot;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingSpots $parkingSpots)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //

        // Validate the request data
        $request->validate([
            'vehicle_fees' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        // Find the parking spot by ID
        $parkingSpot = ParkingSpots::find($id);

        if (! $parkingSpot) {
            return response()->json(['message' => 'Parking spot not found'], 404);
        }

        // Update the parking spot fields
        $parkingSpot->update([
            'vehicle_fees' => $request->input('vehicle_fees'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Parking spot updated successfully', 'data' => $parkingSpot]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'slot_name' => 'required|string|max:255',
            'available_time' => 'required|string',
            'photos' => 'string',
            'google_map' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',

            'available_slots' => 'required|integer',
            'from_date_time' => 'required|string',
            'to_date_time' => 'required|string',
            'nearby_places' => 'required|string',
            'vehicle_fees' => 'required|string',

        ]);

        // Find the parking spot by its ID
        $parkingSpot = ParkingSpots::findOrFail($id);

        // Update the parking spot with the request data
        $parkingSpot->update($request->all());

        // Save photos if there are any
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public/photos'); // Adjust the storage path as needed
                $parkingSpot->photos()->create(['photo_path' => $path]);
            }
        }

        return $parkingSpot;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            // Find the parking spot by ID
            $parkingSpot = ParkingSpots::findOrFail($id);

            // Delete the parking spot
            $parkingSpot->delete();

            return response()->json(['message' => 'Parking spot deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete parking spot'], 500);
        }
    }

    // public function getDateTime(Request $request)
    // {
    //     $request->validate([
    //         'from_datetime' => 'required|date_format:Y-m-d H:i:s',
    //         'to_datetime' => 'required|date_format:Y-m-d H:i:s',
    //     ]);

    //       // Parse the request datetimes into Carbon instances
    // $startDatetime = Carbon::parse($request->from_datetime);
    // $endDatetime = Carbon::parse($request->to_datetime);

    //     $list = ParkingSpots::all();

    //        // Filter the rows based on exact match between start_datetime and end_datetime
    //  $filteredRows = array_filter($list->toArray(),function ($row) use ($startDatetime, $endDatetime) {
    //     // Convert the start and end datetimes of the row to Carbon instances
    //     $rowStartDatetime = Carbon::parse($row->from_date_time);
    //     $rowEndDatetime = Carbon::parse($row->to_date_time);

    //     // return $startDatetime->gte($rowStartDatetime) && $endDatetime->lte($rowEndDatetime);
    //     return $rowEndDatetime >= $startDatetime && $rowStartDatetime <= $endDatetime;

    // });

    // // Return the filtered rows as JSON response

    // return $filteredRows;
    // }

    public function getDateTime(Request $request)
    {
        $request->validate([
            'from_datetime' => 'required|date_format:Y-m-d H:i:s',
            'to_datetime' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $fromDateTime = $request->from_datetime;
        $toDateTime = $request->to_datetime;

        // Filter records based on the exact match of the time range
        $filteredSpots = ParkingSpots::where(function ($query) use ($fromDateTime, $toDateTime) {
            $query->where('from_date_time', '<=', $toDateTime)
                ->where('to_date_time', '>=', $fromDateTime);
        })->get();

        return response()->json($filteredSpots);
    }

    public function getBookingDetail($id)
    {
        // $booking = ParkingSpots::findOrFail($id); // Retrieve booking by ID
        // $parkingSpots = ParkingSpots::with(['photos', 'authOwner'])->get();
        $booking = ParkingSpots::with(['photos', 'authOwner'])->findOrFail($id);

        return response()->json($booking);
    }
}
