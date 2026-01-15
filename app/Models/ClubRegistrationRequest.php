<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'officer_id',
        'club_name',
        'department',
        'nature',
        'rationale',
        'recommended_adviser',
        'constitution_file',
        'officers_list_file',
        'activities_plan_file',
        'budget_proposal_file',
        'status',
        'rejection_reason',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'submitted_at',
        'current_approval_step',
        'verified_by_osa',
        'verified_by_osa_at',
        'verified_by_osa_user',
        'noted_by_director',
        'noted_by_director_at',
        'noted_by_director_user',
        'approved_by_vp',
        'approved_by_vp_at',
        'approved_by_vp_user',
        'endorsed_by_dean',
        'endorsed_by_dean_at',
        'endorsed_by_dean_user',
        'approved_by_psg_council',
        'approved_by_psg_council_at',
        'approved_by_psg_council_user',
        'endorsed_by_psg_council_adviser',
        'endorsed_by_psg_council_adviser_at',
        'endorsed_by_psg_council_adviser_user',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'verified_by_osa_at' => 'datetime',
        'noted_by_director_at' => 'datetime',
        'approved_by_vp_at' => 'datetime',
        'endorsed_by_dean_at' => 'datetime',
        'approved_by_psg_council_at' => 'datetime',
        'endorsed_by_psg_council_adviser_at' => 'datetime',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }
}



