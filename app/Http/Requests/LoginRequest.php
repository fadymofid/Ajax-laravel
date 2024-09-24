<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    // Authorize the request
    public function authorize()
    {
        return true;  // Allow all users to make this request
    }

    // Define the validation rules for the login form
    public function rules()
    {
        return [
            'email' => 'required|email',  // Validate email field
            'password' => 'required|string|min:6',  // Validate password
        ];
    }

    // Custom error messages for the validation rules
    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',  // Custom message if email is not provided
            'email.email' => 'The email must be a valid email address.',  // Custom message for invalid email
            'password.required' => 'The password field is required.',  // Custom message for missing password
        ];
    }
}
