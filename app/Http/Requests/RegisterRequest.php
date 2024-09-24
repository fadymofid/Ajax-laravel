<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to register
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email', // Validate email
            'phone' => 'nullable|string|unique:users,phone', // Validate phone number, make it nullable
            'password' => 'required|string|min:6|confirmed', // Ensure password confirmation
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'phone.unique' => 'The phone number has already been taken.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
