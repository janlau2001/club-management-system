<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Officer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'suffix',
        'username',
        'department',
        'club_status',
        'current_club',
        'year_level',
        'course',
        'student_id',
        'phone',
        'position',
        'email',
        'password',
        'registration_status',
    ];

    public function clubRegistrationRequest()
    {
        return $this->hasOne(ClubRegistrationRequest::class);
    }

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Format phone number to readable format (XXXX XXX XXXX)
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $this->phone);
        
        // If it's 11 digits starting with 09, format it
        if (strlen($phone) === 11 && str_starts_with($phone, '09')) {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7, 4);
        }
        
        return $this->phone; // Return as-is if not in expected format
    }

    /**
     * Set phone number (store as digits only)
     */
    public function setPhoneAttribute($value)
    {
        if (!$value) {
            $this->attributes['phone'] = null;
            return;
        }
        
        // Remove all non-digit characters and store as-is
        $phone = preg_replace('/\D/', '', $value);
        $this->attributes['phone'] = $phone;
    }
}
