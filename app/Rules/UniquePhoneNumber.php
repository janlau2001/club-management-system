<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniquePhoneNumber implements ValidationRule
{
    protected $excludeId;
    protected $excludeTable;

    public function __construct($excludeId = null, $excludeTable = null)
    {
        $this->excludeId = $excludeId;
        $this->excludeTable = $excludeTable;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-digit characters for comparison
        $normalizedPhone = preg_replace('/\D/', '', $value);
        
        // Tables that have phone numbers
        $tables = [
            'officers' => 'phone',
            'club_users' => 'phone'
        ];

        foreach ($tables as $table => $column) {
            // Skip the excluded table and ID if provided (for edit mode)
            if ($this->excludeTable === $table && $this->excludeId) {
                $query = DB::table($table)
                    ->where($column, $normalizedPhone)
                    ->where('id', '!=', $this->excludeId);
            } else {
                $query = DB::table($table)->where($column, $normalizedPhone);
            }

            if ($query->exists()) {
                $tableNames = [
                    'officers' => 'officers',
                    'club_users' => 'club members'
                ];
                
                $fail("This phone number is already registered by {$tableNames[$table]} in the system.");
                return;
            }
        }
    }
}
