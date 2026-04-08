<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubActivity extends Model
{
    use HasFactory;

    protected $table = 'club_activities';

    protected $fillable = [
        'club_id',
        'title',
        'description',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
