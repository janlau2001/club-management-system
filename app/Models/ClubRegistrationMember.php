<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRegistrationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'name',
        'student_id',
        'email',
        'year_level',
    ];

    public function registration()
    {
        return $this->belongsTo(ClubRegistrationRequest::class, 'registration_id');
    }
}
