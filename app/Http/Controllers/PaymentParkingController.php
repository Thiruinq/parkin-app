<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CancelledBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentParkingController extends Controller
{
    public function sendBookingEmail($senderName, $senderEmail, $subject, $textContent, $bookingId)
    {

        try {

            $bookingData = Booking::with(['parkingSpots.authOwner', 'parkingSpots.photos', 'user', 'cancelledBooking'])
                ->where('id', $bookingId)
                ->get();

            Log::Info(print_r($bookingData, true));

            // return $bookingData;
            // $booking = Booking::findOrFail($bookingId);
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

            Mail::send('emails.booking', $data, function ($message) use ($recipientName, $recipientEmail) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Booked parking spots');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return $e->getMessage();
            // return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
        }
    }

    public function sendRefundEmail($senderName, $senderEmail, $subject, $textContent, $bookingId)
    {

        try {

            $bookingData = Booking::with(['parkingSpots.authOwner', 'parkingSpots.photos', 'user', 'cancelledBooking'])
                ->where('id', $bookingId)
                ->get();
            // $booking = Booking::findOrFail($bookingId);

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

            Mail::send('emails.refund', $data, function ($message) use ($recipientName, $recipientEmail) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Refund parking spots');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return $e->getMessage();
            // return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getPaymentBooking(Request $request)
    {
        if ($request->has('booking_id')) {
            $booking = Booking::with(['parkingSpots.authOwner', 'user'])->find($request->input('booking_id'));

            $success = config('app.frontend_url').'/booking-history?booking='.$booking->id.'&paypal=success';
            $cancel = config('app.frontend_url').'/booking-history?booking='.$booking->id.'&paypal=cancel';

            $provider = new PayPalClient;

            $provider->setCurrency('USD');
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => $success,
                    'cancel_url' => $cancel,
                ],
                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $booking->amount_paid,
                        ],
                    ],
                ],
            ]);

            if (isset($response['id']) && $response['id'] != null) {

                $booking->pay_id = $response['id'];
                $booking->update();

                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        $return['link'] = $links['href'];

                        return response()->json($return, 200);
                    }
                }
            }
            $return['error'] = 'Something went wrong.';

            return response()->json($return, 200);
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function getPaymentReturn(Request $request)
    {
        $status = 200; // Set default status to 200
        $return = [
            'status' => 'error',
            'message' => 'Record not found',
        ];
        if ($request->has('booking_id')) {
            $booking = Booking::with(['parkingSpots.authOwner', 'user'])->find($request->input('booking_id'));
            if ($booking) {
                $provider = new PayPalClient;
                $provider->getAccessToken();
                $response = $provider->capturePaymentOrder($request->token);
                Log::info(print_r($response, true));
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    $booking->capture_id = $response['purchase_units'][0]['payments']['captures'][0]['id'];
                    $booking->payment = json_encode($response);
                    $today = Carbon::now()->format('Y-m-d H:i:s');
                    $booking->payed_on = $today;
                    $booking->status = 'Booked';
                    $booking->update();

                    // $mail_status = $this->$mail_status = $this->sendEmail('Hema', 'm.hema10@gmail.com', 'ParkMyDrive', 'ravikumar.inq@gmail.com', 'Laravel Test Mail', 'A test mail');
                    $mail_status = $this->sendBookingEmail(

                        'ParkMyDrive',
                        'ravikumar.inq@gmail.com',
                        'Laravel Test Mail',
                        'This is a test email message.',
                        $booking->id, // Pass booking_id here

                    );
                    $return = [
                        'status' => 'success',
                        'message' => 'Payment successful',
                        'mail_status' => $mail_status,
                    ];
                } else {
                    // Handle if payment status is not 'COMPLETED'
                    $return = [
                        'status' => 'error',
                        'message' => 'Payment was not completed successfully',
                    ];
                    $status = 400; // Set status to indicate a bad request
                }
            } else {
                // Handle if booking record is not found
                $return = [
                    'status' => 'error',
                    'message' => 'Booking record not found',
                ];
                $status = 404; // Set status to indicate resource not found
            }
        }

        return response()->json($return, $status);
    }

    /**
     * Display a listing of the resource.
     */
    public function getPaymentRefund(Request $request)
    {
        $status = 404;
        $return = [
            'status' => 'error',
            'message' => 'Record not found',
        ];
        if ($request->has('booking_id')) {
            $booking = Booking::with(['parkingSpots.authOwner', 'user'])
                ->find($request->input('booking_id'));
            $booking_id = 'BOOKING_'.$request->input('booking_id');
            if ($booking) {

                $cancelledBooking = CancelledBooking::where('booking_id', $booking->id)->first();
                if ($cancelledBooking) {
                    $paypal = new PayPalClient;
                    $paypal->getAccessToken();

                    // Transaction ID of the payment you want to refund
                    $transactionId = $booking->capture_id;

                    // Amount to refund
                    $cancelledBooking->refund_amount = $refundAmount = $request->input('refund_amount'); // This is the partial refund amount

                    // Optional note for the refund
                    $cancelledBooking->reason_for_refund = $note = $request->reason_for_refund;

                    try {

                        $response = $paypal->refundCapturedPayment(
                            $transactionId,
                            $booking_id,
                            $refundAmount,
                            $note
                        );

                        // Handle success
                        Log::Info(print_r($response, true));
                        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                            Log::Info(print_r($response, true));
                            $today = Carbon::now()->format('Y-m-d H:i:s');
                            $cancelledBooking->refund_on = $today;
                            $cancelledBooking->refund_status = $response['status'];
                        }
                        $cancelledBooking->update();
                        $status = 200;
                        $mail_status = $this->sendRefundEmail(
                            'ParkMyDrive',
                            'ravikumar.inq@gmail.com',
                            'Laravel Test Mail',
                            'This is a test email message.',
                            $booking->id, // Pass booking_id here
                        );

                        $return = [
                            'status' => 'success',
                            'message' => 'Successfully Refunded',
                        ];
                    } catch (\Exception $e) {
                        $status = 403;

                        $return = [
                            'status' => 'error',
                            'message' => $e->getMessage(),
                        ];
                    }
                }
            }
        }

        return response()->json($return, $status);
    }
}
