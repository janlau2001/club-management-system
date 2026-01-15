<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueStudentId implements ValidationRule
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
        $tables = [
            'officers' => 'student_id',
            'club_users' => 'student_id'
        ];

        foreach ($tables as $table => $column) {
            // Skip the excluded table and ID if provided
            if ($this->excludeTable === $table && $this->excludeId) {
                $query = DB::table($table)
                    ->where($column, $value)
                    ->where('id', '!=', $this->excludeId);
            } else {
                $query = DB::table($table)->where($column, $value);
            }

            if ($query->exists()) {
                $tableNames = [
                    'officers' => 'officers',
                    'club_users' => 'club members'
                ];
                
                $fail("This Student ID is already registered as {$tableNames[$table]} in the system.");
                return;
            }
        }
    }
}
