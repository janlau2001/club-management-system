<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class Officer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'suffix',
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
        'email_verified_at',
        'remember_token',
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
            'email_verified_at' => 'datetime',
            'has_middle_initial' => 'boolean',
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

    /**
     * Override the email verification notification to use club routes
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new class extends VerifyEmail {
            protected function verificationUrl($notifiable)
            {
                return URL::temporarySignedRoute(
                    'club.verification.verify',
                    now()->addMinutes(60),
                    [
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ]
                );
            }
        });
    }
}
