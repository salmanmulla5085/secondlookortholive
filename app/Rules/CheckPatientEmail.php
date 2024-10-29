<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckPatientEmail implements ValidationRule
{
    // Implement the validate method
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Check if the email exists and the user type is 'patient'
        $userExists = DB::table('dbl_users')
            ->where('email_address', $value)
            ->where('user_type', 'patient')
            ->exists();

        if (!$userExists) {
            // Fail the validation with a custom error message
            $fail('The provided email address does not belong to a patient.');
        }
    }
}