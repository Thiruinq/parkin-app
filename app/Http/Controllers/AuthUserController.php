<?php

namespace App\Http\Controllers;

use App\Models\AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Log;

class AuthUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        return 'hi';
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthUser $authUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthUser $authUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthUser $authUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthUser $authUser)
    {
        //
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function sendRegisterEmail($name, $email)
    {
        try {
            $recipientName = $name;
            $data = [
                'recipientName' => $recipientName,
                'recipientEmail' => $email,
                'name' => $recipientName,
            ];

            Mail::send('emails.register', $data, function ($message) use ($recipientName, $email) {
                $message->to($email, $recipientName)
                    ->subject('Registration');
            });

            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:auth_users,email',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required',
            'mobile' => 'required|string|min:10|max:10',
            'role' => 'number',
        ]);

        $user = new AuthUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
            'role' => $request->filled('role') ? $request->role : 0,
        ]);

        if ($user->save()) {
            // Automatically log in the user
            Auth::login($user);
            $mail_status = $this->sendRegisterEmail(
                $request->name,
                $request->email
            );
            // Generate token for the user
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user and logged in!',
                'accessToken' => $token,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = AuthUser::where('email', $request->email)->first();

            return response()->json([
                'accessToken' => $user->createToken('api_token')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }

        return response()->json(['message' => 'This action is unauthorized.'], 401);

    }

    public function logout(Request $request)
    {
        /** @var AuthUser $user */
        $user = Auth::guard('web')->user();
        // $user = $request->user();
        // Revoke all tokens issued to the user
        $user->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function user()
    {
        return Auth()->user();
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' => __($status)], 200)
                    : response()->json(['message' => __($status)], 400);
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['message' => __($status)], 200)
                    : response()->json(['message' => __($status)], 400);
    }
}
