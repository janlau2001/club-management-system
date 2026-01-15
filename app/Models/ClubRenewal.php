<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubRenewal extends Model
{
    protected $fillable = [
        'club_id',
        'academic_year',
        'last_renewal_date',
        'department',
        'nature',
        'faculty_adviser',
        'rationale',
        'officers_list_file',
        'activities_plan_file',
        'budget_proposal_file',
        'constitution_file',
        'status',
        'submitted_at',
        // President preparation fields
        'prepared_by_president',
        'prepared_by_president_at',
        'prepared_by_president_user',
        // Adviser certification fields
        'certified_by_adviser',
        'certified_by_adviser_at',
        'certified_by_adviser_user',
        // PSG Council review fields
        'reviewed_by_psg',
        'reviewed_by_psg_at',
        'reviewed_by_psg_user',
        // Dean noting fields
        'noted_by_dean',
        'noted_by_dean_at',
        'noted_by_dean_user',
        // Director endorsement fields
        'endorsed_by_osa',
        'endorsed_by_osa_at',
        'endorsed_by_osa_user',
        // VP approval fields
        'approved_by_vp',
        'approved_by_vp_at',
        'approved_by_vp_user',
        // Final approval fields
        'approved_at',
        'approved_by',
        'final_approved_by',
        'final_approved_at',
        // Rejection fields
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'last_renewal_date' => 'date',
        'submitted_at' => 'datetime',
        'prepared_by_president' => 'boolean',
        'prepared_by_president_at' => 'datetime',
        'certified_by_adviser' => 'boolean',
        'certified_by_adviser_at' => 'datetime',
        'reviewed_by_psg' => 'boolean',
        'reviewed_by_psg_at' => 'datetime',
        'noted_by_dean' => 'boolean',
        'noted_by_dean_at' => 'datetime',
        'endorsed_by_osa' => 'boolean',
        'endorsed_by_osa_at' => 'datetime',
        'approved_by_vp' => 'boolean',
        'approved_by_vp_at' => 'datetime',
        'approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the club that owns the renewal
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the current status with proper formatting
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending_internal' => 'Pending Internal Approval',
            'pending_admin' => 'Pending Admin Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status)
        };
    }

    /**
     * Check if renewal can be prepared by president
     */
    public function canBePrepared(): bool
    {
        return in_array($this->status, ['draft', 'pending_internal']) && !$this->prepared_by_president;
    }

    /**
     * Check if renewal can be certified by adviser
     */
    public function canBeCertified(): bool
    {
        return $this->prepared_by_president && !$this->certified_by_adviser;
    }

    /**
     * Get the next required action
     */
    public function getNextActionAttribute(): string
    {
        if (!$this->prepared_by_president) {
            return 'Needs President Preparation';
        }
        if (!$this->certified_by_adviser) {
            return 'Needs Adviser Certification';
        }
        if ($this->status === 'pending_admin') {
            return 'Pending Admin Review';
        }
        if ($this->status === 'approved') {
            return 'Approved';
        }
        if ($this->status === 'rejected') {
            return 'Rejected';
        }
        return 'In Progress';
    }

    /**
     * Determine if renewal is ready for admin review chain
     */
    public function isReadyForAdminApproval(): bool
    {
        return (bool) ($this->prepared_by_president && $this->certified_by_adviser && $this->status === 'pending_admin');
    }

    /**
     * Approve the renewal and update the club's last renewal date
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? 'System'
        ]);

        // Update the club's last renewal date to reset the renewal cycle
        $this->club->renewClub();
    }
}
