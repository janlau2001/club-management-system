<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ClubUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'club_id',
        'name',
        'email',
        'phone',
        'password',
        'student_id',
        'role',
        'position',
        'department',
        'year_level',
        'course',
        'is_online',
        'last_activity',
        'joined_date',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_online' => 'boolean',
        'last_activity' => 'datetime',
        'joined_date' => 'date',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function isOfficer()
    {
        return $this->role === 'officer';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    public function isAdviser()
    {
        return $this->role === 'adviser';
    }

    public function hasManagementAccess()
    {
        return $this->role === 'officer' || $this->role === 'adviser';
    }

    public function hasRestrictedManagementAccess()
    {
        // Give all officers and advisers full management access
        return $this->role === 'officer' || $this->role === 'adviser';
    }

    public function hasAccessDuringSuspension()
    {
        return in_array($this->position, ['President', 'Vice President']) || $this->role === 'adviser';
    }

    public function isOnline()
    {
        return $this->is_online && $this->last_activity && $this->last_activity->diffInMinutes(now()) <= 5;
    }

    public function updateOnlineStatus()
    {
        $this->update([
            'is_online' => true,
            'last_activity' => now(),
        ]);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update club member count when a ClubUser is created
        static::created(function ($clubUser) {
            $clubUser->updateClubMemberCount();
        });

        // Update club member count when a ClubUser is deleted
        static::deleted(function ($clubUser) {
            $clubUser->updateClubMemberCount();
        });
    }

    /**
     * Update the club's member count
     */
    public function updateClubMemberCount()
    {
        if ($this->club) {
            $totalMembers = ClubUser::where('club_id', $this->club_id)->count();
            $this->club->update(['member_count' => $totalMembers]);
        }
    }

    public function setOffline()
    {
        $this->update([
            'is_online' => false,
        ]);
    }

    /**
     * Get the display role showing specific position for officers and advisers
     */
    public function getDisplayRole()
    {
        if ($this->role === 'officer' && $this->position) {
            return $this->position;
        }
        
        if ($this->role === 'adviser' && $this->position) {
            return $this->position;
        }
        
        // Fallback to capitalized role
        return ucfirst($this->role);
    }

    /**
     * Get the role badge HTML with appropriate styling
     */
    public function getRoleBadgeHtml()
    {
        $displayRole = $this->getDisplayRole();
        
        if ($this->role === 'adviser') {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">' . $displayRole . '</span>';
        } elseif ($this->role === 'officer') {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">' . $displayRole . '</span>';
        } else {
            return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Member</span>';
        }
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
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
