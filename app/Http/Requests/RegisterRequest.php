<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
   
    public function rules()
    {        
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email_address' => 'required|string|email|max:100|unique:users,email_address',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
