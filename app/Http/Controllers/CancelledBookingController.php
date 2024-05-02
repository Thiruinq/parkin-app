<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CancelledBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;

class CancelledBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {

            $bookings = CancelledBooking::with(['booking.user', 'booking.parkingSpots'])->get();

            return response()->json($bookings->all(), 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json(['message' => 'Failed to fetch cancelled bookings'], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Retrieve the booking to be cancelled
        $booking = Booking::findOrFail($request->booking_id);

        // Validate and process the cancellation request
        $request->validate([
            'cancelled_by' => 'required',
            'total_hours' => 'required|integer',
            'cancelled_date' => 'required|date',
            'refund_status' => 'required',
            'reason_for_cancellation' => 'required',
            // Add validation rules for other cancellation fields
        ]);

        // Create a new CancelledBooking instance
        $cancelledBooking = new CancelledBooking([
            'booking_id' => $request->booking_id,
            'cancelled_by' => $request->cancelled_by,
            'total_hours' => $request->total_hours,
            'cancelled_date' => $request->cancelled_date,
            'refund_status' => $request->refund_status,
            'reason_for_cancellation' => $request->reason_for_cancellation,
            // Set other cancellation fields
            'refund_amount' => 0, // Set the refund_amount
            'reason_for_refund' => $request->reason_for_refund ?? null,
            'refund_on' => $request->refund_on ?? null,
            'comments' => $request->comments ?? null,
        ]);

        // Save the cancelled booking record
        $booking->cancelledBooking()->save($cancelledBooking);

        // Optionally, update the booking status to 'Cancelled'
        $booking->status = 'Cancelled';
        $booking->save();
        $mail_status = $this->sendCancelEmail(
            'ParkMyDrive',
            'ravikumar.inq@gmail.com',
            'Laravel Test Mail',
            'This is a test email message.',
            $booking->id, // Pass booking_id here

        );

        // Return a response indicating success
        return response()->json(['message' => 'Booking cancelled successfully']);
    }

    public function sendCancelEmail($senderName, $senderEmail, $subject, $textContent, $bookingId)
    {

        try {

            $bookingData = Booking::with(['parkingSpots.authOwner', 'parkingSpots.photos', 'user', 'cancelledBooking'])
                ->where('id', $bookingId)
                ->get();

            $bookingVal = json_decode($bookingData, true);

            foreach ($bookingVal as $booking) {
                $recipientName = $booking['user']['name'];
                $recipientEmail = $booking['user']['email'];
            }
            $data = [
                'recipientName' => $recipientName,
                'recipientEmail' => $recipientEmail,
                'bookingId' => $bookingId,
                'bookingData' => $bookingVal,

            ];

            Mail::send('emails.cancel', $data, function ($message) use ($recipientName, $recipientEmail) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Cancel parking spots');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return $e->getMessage();
            // return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cancelledBooking = CancelledBooking::findOrFail($id);

        $request->validate([
            'refund_status' => 'required|string',
            'refund_amount' => 'required|numeric',
            'comments' => 'required|string',
            // Add any other validation rules here
        ]);

        $cancelledBooking->update([
            'refund_status' => $request->refund_status,
            'refund_amount' => $request->refund_amount,
            'comments' => $request->comments,
            // Update any other fields here
        ]);

        return response()->json(['message' => 'Cancelled booking updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CancelledBooking $cancelledBooking)
    {
        //
    }
}
