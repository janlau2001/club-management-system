<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VpController extends Controller
{
    public function dashboard(Request $request)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Get organizations data with filters
        $query = Club::with(['clubUsers']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('club_type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('department') && $request->department !== 'all') {
            $query->where('department', $request->department);
        }

        $clubs = $query->get();

        // Calculate statistics
        $totalOrganizations = Club::count();
        $activeOrganizations = Club::where('status', 'active')->count();
        $pendingApprovals = ClubRegistrationRequest::where('current_approval_step', 'vp')
            ->where('noted_by_director', true)
            ->where('approved_by_vp', false)
            ->count();
        $approvedByMe = ClubRegistrationRequest::where('approved_by_vp', true)->count();

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('vp.dashboard', compact(
            'clubs',
            'totalOrganizations',
            'activeOrganizations',
            'pendingApprovals',
            'approvedByMe',
            'clubTypes',
            'departments',
            'statuses'
        ));
    }



    public function showOrganization(Club $club)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }
        
        $club->load(['clubUsers']);
        return view('vp.organization-details', compact('club'));
    }

    public function approvals(Request $request)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }
        
        // Get status filter from request, default to 'pending'
        $status = $request->get('status', 'pending');
        
        // Build query based on status
        $query = ClubRegistrationRequest::with('officer');
        
        if ($status === 'pending') {
            // Show ALL pending registrations (VP can view all, but can only approve after prior steps)
            $query->where('status', 'pending')
                  ->where('approved_by_vp', false);
        } elseif ($status === 'approved') {
            // Show registrations that VP has approved
            $query->where('approved_by_vp', true);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Get counts for tabs
        $pendingCount = ClubRegistrationRequest::where('status', 'pending')
            ->where('approved_by_vp', false)
            ->count();
        $approvedCount = ClubRegistrationRequest::where('approved_by_vp', true)->count();

        return view('vp.approvals', compact('registrations', 'status', 'pendingCount', 'approvedCount'));
    }

    public function showApproval(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }
        
        $registration->load('officer');
        return view('vp.approval-details', compact('registration'));
    }

    public function approve(Request $request, ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Check if all prior approval steps are completed
        if (!$registration->endorsed_by_dean) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot approve: Dean endorsement is required first.'
            ]);
        }

        if (!$registration->approved_by_psg_council) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot approve: PSG Council approval is required first.'
            ]);
        }

        if (!$registration->noted_by_director) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot approve: Director noting is required first.'
            ]);
        }

        $request->validate([
            'password' => 'required'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password. Please try again.'
            ]);
        }

        // Update VP approval
        $registration->update([
            'approved_by_vp' => true,
            'approved_by_vp_at' => now(),
            'approved_by_vp_user' => $admin->name,
            'status' => 'approved',
            'approved_at' => now(),
            'current_approval_step' => null // Final step completed
        ]);

        // Create the actual Club record only if it doesn't already exist
        $existingClub = \App\Models\Club::where('name', $registration->club_name)
            ->where('officer_id', $registration->officer_id)
            ->first();
            
        if (!$existingClub) {
            try {
                $club = \App\Models\Club::create([
                    'name' => $registration->club_name,
                    'department' => $registration->department,
                    'nature' => $registration->nature,
                    'club_type' => $registration->nature, // Use nature as club_type
                    'description' => $registration->rationale,
                    'adviser_name' => $registration->recommended_adviser,
                    'adviser_email' => '', // Set empty string as default
                    'adviser' => $registration->recommended_adviser,
                    'status' => 'active',
                    'registration_date' => now(),
                    'date_registered' => now()->toDateString(),
                    'member_count' => 0, // Will be updated automatically when ClubUser is created
                    'officer_id' => $registration->officer_id
                ]);

                // Add the registering officer as the club president
                $officer = \App\Models\Officer::find($registration->officer_id);
                if ($officer) {
                    \App\Models\ClubUser::create([
                        'club_id' => $club->id,
                        'name' => $officer->name,
                        'email' => $officer->email,
                        'department' => $officer->department,
                        'year_level' => $officer->year_level,
                        'course' => $officer->course,
                        'student_id' => $officer->student_id,
                        'phone' => $officer->phone,
                        'role' => 'officer',
                        'position' => 'President',
                        'password' => $officer->password, // Keep the same password
                        'is_online' => false,
                        'last_activity' => now(),
                        'joined_date' => now()->toDateString(),
                        'status' => 'active'
                    ]);

                    // Update officer status to reflect approved and active club
                    $officer->update([
                        'club_status' => 'active',
                        'registration_status' => 'approved'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create club or add officer: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'VP approval updated but club creation failed: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Club registration has been approved successfully! The club is now officially registered and active.'
        ]);
    }

    public function reject(Request $request, ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $request->validate([
            'password' => 'required',
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password. Please try again.'
            ]);
        }

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'rejected_at' => now(),
            'current_approval_step' => null // Process ended
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Club registration has been rejected.'
        ]);
    }

    public function downloadDocument($id, $type)
    {
        $registration = ClubRegistrationRequest::findOrFail($id);
        
        $filePath = null;
        $fileName = null;
        
        switch ($type) {
            case 'constitution':
                $filePath = $registration->constitution_file;
                $fileName = 'Constitution_' . $registration->club_name . '.pdf';
                break;
            case 'officers_list':
                $filePath = $registration->officers_list_file;
                $fileName = 'Officers_List_' . $registration->club_name . '.pdf';
                break;
            case 'activities_plan':
                $filePath = $registration->activities_plan_file;
                $fileName = 'Activities_Plan_' . $registration->club_name . '.pdf';
                break;
            case 'budget_proposal':
                $filePath = $registration->budget_proposal_file;
                $fileName = 'Budget_Proposal_' . $registration->club_name . '.pdf';
                break;
            default:
                abort(404);
        }
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($filePath, $fileName);
    }

    /**
     * Show renewal approvals page (VP approval - Parallel System)
     */
    public function renewalApprovals()
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Get renewals ready for VP approval (parallel approval system)
        $renewals = ClubRenewal::with('club')
            ->where('status', 'pending_admin')
            ->where('prepared_by_president', true)
            ->where('certified_by_adviser', true)
            ->where('approved_by_vp', false) // Only show renewals VP hasn't approved yet
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vp.renewal-approvals', compact('renewals'));
    }

    /**
     * Show specific renewal approval details
     */
    public function showRenewalApproval(ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        if (!$renewal->isReadyForAdminApproval()) {
            return redirect()->route('vp.renewal-approvals')
                ->with('error', 'This renewal is not ready for admin approval.');
        }

        return view('vp.renewal-approval-details', compact('renewal'));
    }

    /**
     * Approve renewal (VP's action - third step in admin approval)
     */
    public function approveRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate password
        $request->validate([
            'current_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        // VP approves the renewal (parallel admin approval)
        $renewal->update([
            'approved_by_vp' => true,
            'approved_by_vp_at' => now(),
            'approved_by_vp_user' => (session('user')?->name) ?? 'Unknown Admin',
        ]);

        // Check if all admin approvals are complete (parallel approval system)
        $this->checkAndCompleteRenewal($renewal);

        return redirect()->route('vp.renewal-approvals')
            ->with('success', 'Renewal approved successfully! ' . $this->getRemainingApprovals($renewal));
    }

    /**
     * Reject renewal (VP's rejection)
     */
    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'vp_academics') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate password and rejection reason
        $request->validate([
            'current_password' => 'required|string',
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        $renewal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        return redirect()->route('vp.renewal-approvals')
            ->with('success', 'Renewal rejected successfully!');
    }

    /**
     * Check if all admin approvals are complete and mark renewal as approved
     */
    private function checkAndCompleteRenewal(ClubRenewal $renewal)
    {
        // Refresh to get latest data
        $renewal->refresh();

        // Check if all 4 admin approvals are complete
        if ($renewal->reviewed_by_psg && 
            $renewal->noted_by_dean && 
            $renewal->endorsed_by_osa && 
            $renewal->approved_by_vp) {
            
            // All approvals complete - mark as fully approved and update club's last renewal date
            $renewal->approve((session('user')?->name) ?? 'System');
        }
    }

    /**
     * Get message about remaining approvals needed
     */
    private function getRemainingApprovals(ClubRenewal $renewal)
    {
        $renewal->refresh();
        
        $pending = [];
        if (!$renewal->reviewed_by_psg) $pending[] = 'PSG Council';
        if (!$renewal->noted_by_dean) $pending[] = 'Dean';
        if (!$renewal->endorsed_by_osa) $pending[] = 'Director';
        if (!$renewal->approved_by_vp) $pending[] = 'VP Academics';

        if (empty($pending)) {
            return '🎉 All approvals complete! Renewal is now fully approved.';
        }

        return 'Remaining approvals needed: ' . implode(', ', $pending) . '.';
    }
}