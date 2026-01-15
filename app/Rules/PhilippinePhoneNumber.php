<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhilippinePhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-digit characters for validation
        $digitsOnly = preg_replace('/\D/', '', $value);
        
        // Check if it has exactly 11 digits (Philippine mobile with 09 prefix)
        if (strlen($digitsOnly) !== 11) {
            $fail('The :attribute must be exactly 11 digits (e.g., 09171234567).');
            return;
        }
        
        // Check if it starts with 09 (Philippine mobile numbers start with 09)
        if (!str_starts_with($digitsOnly, '09')) {
            $fail('The :attribute must start with 09 (Philippine mobile number format).');
            return;
        }
        
        // Check for valid Philippine mobile prefixes (first 3 digits)
        $validPrefixes = [
            '090', '091', '092', '093', '094', '095', '096', '097', '098', '099'
        ];
        
        // Get the first 3 digits of the full number (e.g., "091" from "09171234567")
        $prefix = substr($digitsOnly, 0, 3);
        if (!in_array($prefix, $validPrefixes)) {
            $fail('The :attribute must be a valid Philippine mobile number.');
        }
    }
}
