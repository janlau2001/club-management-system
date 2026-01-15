<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'violation_type',
        'severity',
        'title',
        'description',
        'evidence',
        'points',
        'status',
        'reported_by',
        'violation_date',
        'resolution_notes',
        'resolved_date'
    ];

    protected $casts = [
        'violation_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function appeals()
    {
        return $this->hasMany(ViolationAppeal::class);
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'minor' => 'bg-yellow-100 text-yellow-800',
            'moderate' => 'bg-orange-100 text-orange-800',
            'major' => 'bg-red-100 text-red-800',
            'critical' => 'bg-red-200 text-red-900'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-gray-100 text-gray-800',
            'confirmed' => 'bg-red-100 text-red-800',
            'dismissed' => 'bg-green-100 text-green-800',
            'appealed' => 'bg-blue-100 text-blue-800'
        };
    }
}
