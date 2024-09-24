<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    // Show the registration form (web)
    public function showRegistrationForm()
    {
        return view('auth.register'); // Ensure this Blade file exists
    }

    // Handle registration for both web and API
    public function register(RegisterRequest $request)
    {
        // Validate the input (already validated by RegisterRequest)
        $validatedData = $request->validated();

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'], // Add email here
            'phone' => $validatedData['phone'], // Use 'phone' field
            'password' => Hash::make($validatedData['password']),
            'role' => 'client', // Default role
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }


}
