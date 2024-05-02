<?php

namespace App\Http\Controllers;

use App\Models\AuthAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{
    public function login(Request $request)
    {

        // dd($request->all());
        // Validate request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt(['username' => $validatedData['username'], 'password' => $validatedData['password']])) {
            // Authentication successful
            $admin = Auth::guard('admin')->user(); // Retrieve the authenticated admin user
            $token = $admin->createToken('api_token')->plainTextToken; // Generate access token

            return response()->json(['admin' => $admin, 'access_token' => $token], 200);
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
        $user = new AuthAdmin();
        $user->username = $validatedData['username'];
        $user->password = $validatedData['password']; // Hash the password for security
        $user->email = $validatedData['email'];
        $user->save();

        return response()->json(['message' => 'Admin User created successfully'], 201);
    }

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
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthAdmin $authAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthAdmin $authAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthAdmin $authAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthAdmin $authAdmin)
    {
        //
    }
}
