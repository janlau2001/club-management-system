<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubApplication extends Model
{
    protected $fillable = [
        'club_id',
        'first_name',
        'last_name',
        'suffix',
        'age',
        'gender',
        'phone_number',
        'student_id',
        'department',
        'year_level',
        'position',
        'email',
        'password',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the club that this application belongs to
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
