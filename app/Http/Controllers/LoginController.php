<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form for web users
    public function showLoginForm()
    {
        return view('login');  // Return the login Blade template
    }

    // Handle login for web users
    public function login(LoginRequest $request)
    {
        // Attempt to log the user in using the provided email and password
        if (Auth::attempt($request->only('email', 'password'))) {
            // Redirect the user based on their role
            if (auth()->user()->role === 'client') {  // If the user is a client
                return redirect()->route('');  // Redirect to the notifications page
            } elseif (auth()->user()->role === 'admin') {  // If the user is an admin
                return redirect()->route('admin.categories.create');  // Redirect to the create category page
              // Redirect to the admin's users page
            } else {
                // If the user role is unrecognized, return an error
                return redirect()->back()->withErrors(['email' => 'User role not recognized.']);
            }
        }

        // If login is unsuccessful, return back with an error message
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle logout for web users
    public function logout(Request $request)
    {
        Auth::logout();  // Log the user out
        $request->session()->invalidate();  // Invalidate the session to prevent session fixation attacks
        $request->session()->regenerateToken();  // Regenerate CSRF token for security

        // Redirect to login page with a success message
        return redirect('/login')->with('status', 'Successfully logged out!');
    }
}
