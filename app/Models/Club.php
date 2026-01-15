<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'nature',
        'club_type',
        'description',
        'adviser_name',
        'adviser_email',
        'adviser',
        'date_registered',
        'registration_date',
        'last_renewal_date',
        'member_count',
        'status',
        'officer_id',
    ];

    protected $casts = [
        'date_registered' => 'datetime',
        'registration_date' => 'datetime',
        'last_renewal_date' => 'datetime',
    ];

    public function clubUsers()
    {
        return $this->hasMany(ClubUser::class);
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }

    public function members()
    {
        return $this->hasMany(ClubUser::class)->where('role', 'member');
    }

    public function officers()
    {
        return $this->hasMany(ClubUser::class)->where('role', 'officer');
    }

    public function clubMembers()
    {
        return $this->hasMany(ClubUser::class)->where('role', 'member');
    }

    public function clubOfficers()
    {
        return $this->hasMany(ClubUser::class)->where('role', 'officer');
    }

    public function onlineUsers()
    {
        return $this->hasMany(ClubUser::class)->where('is_online', true)
            ->where('last_activity', '>=', now()->subMinutes(5));
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'suspended' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the date when the next renewal is due (1 year from last renewal or registration)
     */
    public function getRenewalDueDateAttribute()
    {
        $baseDate = $this->last_renewal_date ?? $this->date_registered;
        return $baseDate->addYear();
    }

    /**
     * Get the number of days until renewal is due
     */
    public function getDaysUntilRenewalAttribute()
    {
        $dueDate = $this->renewal_due_date;
        $today = now();
        
        return $today->diffInDays($dueDate, false); // false means it can be negative (overdue)
    }

    /**
     * Check if the club's renewal is overdue
     */
    public function isRenewalOverdue()
    {
        return $this->days_until_renewal < 0;
    }

    /**
     * Check if the club's renewal is due soon (within 30 days)
     */
    public function isRenewalDueSoon()
    {
        return $this->days_until_renewal <= 30 && $this->days_until_renewal >= 0;
    }

    /**
     * Update the last renewal date and reset the renewal cycle
     */
    public function renewClub()
    {
        $this->update([
            'last_renewal_date' => now(),
            'status' => 'active'
        ]);
    }

    /**
     * Get violations for this club
     */
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    /**
     * Get total violation points
     */
    public function getTotalViolationPointsAttribute()
    {
        return $this->violations()->where('status', 'confirmed')->sum('points');
    }

    /**
     * Get risk level based on violation points
     */
    public function getRiskLevelAttribute()
    {
        $points = $this->total_violation_points;
        
        if ($points >= 100) return 'critical';
        if ($points >= 50) return 'high';
        if ($points >= 20) return 'medium';
        if ($points > 0) return 'low';
        return 'none';
    }

    /**
     * Get risk color for display
     */
    public function getRiskColorAttribute()
    {
        return match($this->risk_level) {
            'critical' => 'bg-red-500 text-white',
            'high' => 'bg-orange-500 text-white',
            'medium' => 'bg-yellow-500 text-white',
            'low' => 'bg-blue-500 text-white',
            'none' => 'bg-green-500 text-white'
        };
    }
}

