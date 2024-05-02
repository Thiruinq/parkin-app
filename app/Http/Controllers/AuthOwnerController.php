<?php

namespace App\Http\Controllers;

use App\Models\AuthOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Log;

class AuthOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {

        // dd($request->all());
        // Validate request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('owner')->attempt(['username' => $validatedData['username'], 'password' => $validatedData['password']])) {
            // Authentication successful
            $owner = Auth::guard('owner')->user(); // Retrieve the authenticated admin user
            $token = $owner->createToken('api_token')->plainTextToken; // Generate access token

            return response()->json(['owner' => $owner, 'access_token' => $token], 200);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);

        // Create a new user
        $user = new AuthOwner();
        $user->username = $validatedData['username'];
        $user->password = bcrypt($validatedData['password']); // Hash the password for security
        $user->email = $validatedData['email'];

        if ($user->save()) {
            Auth::guard('owner')->login($user);
            $mail_status = $this->sendRegisterEmail(
                $user->username,
                $user->email
            );
            // Generate access token
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json(['message' => 'Owner User created successfully', 'access_token' => $token], 201);

        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function sendRegisterEmail($name, $email)
    {
        try {
            $recipientName = $name;
            $data = [
                'recipientName' => $recipientName,
                'recipientEmail' => $email,
                'name' => $recipientName,
            ];

            Mail::send('emails.owner_register', $data, function ($message) use ($recipientName, $email) {
                $message->to($email, $recipientName)
                    ->subject('Owner Registration');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
        }
    }

    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthOwner $authOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthOwner $authOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthOwner $authOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthOwner $authOwner)
    {
        //
    }
}
