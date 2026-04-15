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

    public function advisers()
    {
        return $this->hasMany(ClubUser::class)->where('role', 'adviser');
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

    // ──────────────────────────────────────────────────────────────
    //  Fixed annual renewal window: August 1 – August 31
    //  Warning period : August 21 – August 31  (10 days before close)
    //  Grace / appeal : September 1 – September 10
    //  Closed         : September 11 onward (until next August 1)
    // ──────────────────────────────────────────────────────────────

    /**
     * Return the closing date of the renewal window for a given year.
     * The window is Aug 1 – Aug 31; so the deadline is August 31.
     */
    public static function renewalDeadlineForYear(int $year): \Carbon\Carbon
    {
        return \Carbon\Carbon::create($year, 8, 31, 23, 59, 59);
    }

    /**
     * Return the opening date of the renewal window for a given year.
     */
    public static function renewalOpenDateForYear(int $year): \Carbon\Carbon
    {
        return \Carbon\Carbon::create($year, 8, 1, 0, 0, 0);
    }

    /**
     * Return the last day clubs can submit an appeal/late renewal (Sep 10).
     */
    public static function renewalGraceEndForYear(int $year): \Carbon\Carbon
    {
        return \Carbon\Carbon::create($year, 9, 10, 23, 59, 59);
    }

    /**
     * Get the August 31 deadline for the current renewal cycle year.
     * If today is before Aug 1 of the current year we still use the current year
     * (the window hasn't opened yet). If we're past Sep 10 we already use next year
     * so the countdown is toward the next cycle.
     */
    public function getRenewalDueDateAttribute(): \Carbon\Carbon
    {
        $today = now();
        $year  = (int) $today->format('Y');

        $graceEnd = static::renewalGraceEndForYear($year);

        // If we are past Sep 10 of this year, show the next year's deadline
        if ($today->gt($graceEnd)) {
            $year++;
        }

        return static::renewalDeadlineForYear($year);
    }

    /**
     * Days until August 31 (negative = overdue / past deadline).
     */
    public function getDaysUntilRenewalAttribute(): int
    {
        return (int) now()->diffInDays($this->renewal_due_date, false);
    }

    /**
     * Whether the renewal window is currently open (Aug 1 – Aug 31).
     */
    public static function isRenewalWindowOpen(): bool
    {
        $today = now();
        $year  = (int) $today->format('Y');
        return $today->between(
            static::renewalOpenDateForYear($year),
            static::renewalDeadlineForYear($year)
        );
    }

    /**
     * Whether we are in the grace/appeal period (Sep 1 – Sep 10).
     */
    public static function isRenewalGracePeriod(): bool
    {
        $today = now();
        $year  = (int) $today->format('Y');
        $graceStart = static::renewalDeadlineForYear($year)->addSecond();
        return $today->between($graceStart, static::renewalGraceEndForYear($year));
    }

    /**
     * Whether the renewal period is closed (after Sep 10).
     */
    public static function isRenewalClosed(): bool
    {
        return !static::isRenewalWindowOpen() && !static::isRenewalGracePeriod();
    }

    /**
     * Check if the club's renewal is overdue (past Aug 31 and not yet renewed this year).
     */
    public function isRenewalOverdue(): bool
    {
        return $this->days_until_renewal < 0;
    }

    /**
     * Check if we are in the 10-day warning period (Aug 21 – Aug 31).
     */
    public static function isRenewalWarningSoon(): bool
    {
        $today = now();
        $year  = (int) $today->format('Y');
        $warnStart = \Carbon\Carbon::create($year, 8, 21, 0, 0, 0);
        return $today->between($warnStart, static::renewalDeadlineForYear($year));
    }

    /**
     * Check if the club's renewal is due soon (within 10-day warning window).
     */
    public function isRenewalDueSoon(): bool
    {
        return static::isRenewalWarningSoon();
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
     * Get offense count (confirmed violations)
     */
    public function getOffenseCountAttribute()
    {
        return $this->violations()->where('status', 'confirmed')->count();
    }

    /**
     * Get risk level based on offense count (3-strike system)
     */
    public function getRiskLevelAttribute()
    {
        $offenses = $this->offense_count;
        
        if ($offenses >= 3) return 'critical';
        if ($offenses === 2) return 'high';
        if ($offenses === 1) return 'low';
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
            'low' => 'bg-blue-500 text-white',
            'none' => 'bg-green-500 text-white'
        };
    }
}

