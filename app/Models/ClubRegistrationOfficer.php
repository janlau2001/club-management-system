<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRegistrationOfficer extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'name',
        'position',
        'email',
    ];

    public function registration()
    {
        return $this->belongsTo(ClubRegistrationRequest::class, 'registration_id');
    }
}
