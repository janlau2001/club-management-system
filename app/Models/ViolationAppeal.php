<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationAppeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_id',
        'club_id',
        'submitted_by',
        'appeal_reason',
        'supporting_documents',
        'status',
        'review_notes',
        'reviewed_by',
        'submitted_at',
        'reviewed_at'
    ];

    protected $casts = [
        'supporting_documents' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'text-yellow-600 bg-yellow-100',
            'under_review' => 'text-blue-600 bg-blue-100', 
            'approved' => 'text-green-600 bg-green-100',
            'rejected' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }
}
