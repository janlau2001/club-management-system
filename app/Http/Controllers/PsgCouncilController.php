<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PsgCouncilController extends Controller
{
    public function dashboard(Request $request)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
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

        // Paginate to support view pagination controls
        $clubs = $query->paginate(12);

        // Calculate statistics
        $totalOrganizations = Club::count();
        $activeOrganizations = Club::where('status', 'active')->count();
        $pendingApprovals = ClubRegistrationRequest::where('status', 'pending')
            ->where('endorsed_by_dean', true)
            ->where('approved_by_psg_council', false)->count();
        $approvedByMe = ClubRegistrationRequest::where('approved_by_psg_council', true)->count();

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('psg-council.dashboard', compact(
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
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $club->load(['clubUsers']);
        return view('psg-council.organization-details', compact('club'));
    }

    public function approvals(Request $request)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Get status filter from request, default to 'pending'
        $status = $request->get('status', 'pending');
        
        // Build query based on status
        $query = ClubRegistrationRequest::with('officer');
        
        if ($status === 'pending') {
            // Show all pending registrations (can see but may not approve yet)
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            // Show registrations that PSG Council has approved
            $query->where('approved_by_psg_council', true);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Get counts for tabs
        $pendingCount = ClubRegistrationRequest::where('status', 'pending')->count();
        $approvedCount = ClubRegistrationRequest::where('approved_by_psg_council', true)->count();

        return view('psg-council.approvals', compact('registrations', 'status', 'pendingCount', 'approvedCount'));
    }

    public function showApproval(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $registration->load('officer');
        return view('psg-council.approval-details', compact('registration'));
    }

    public function approve(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Validate password if provided
        $request = request();
        if ($request->filled('current_password')) {
            $admin = session('user');
            if (!$admin || !Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Invalid password.']);
            }
        }

        // Check if already approved by PSG Council
        if ($registration->approved_by_psg_council) {
            return redirect()->route('psg-council.approvals')
                ->with('error', 'Registration has already been approved by PSG Council.');
        }

        // Check if endorsed by Dean first
        if (!$registration->endorsed_by_dean) {
            return redirect()->route('psg-council.approvals')
                ->with('error', 'Registration must be endorsed by Dean first.');
        }

        $registration->update([
            'approved_by_psg_council' => true,
            'approved_by_psg_council_at' => now(),
            'approved_by_psg_council_user' => (session('user')?->name) ?? 'PSG Council Adviser',
            'current_approval_step' => 'director', // Move to next step
        ]);

        return redirect()->route('psg-council.approvals')
            ->with('success', 'Registration approved by PSG Council successfully! It will now proceed to Director for noting.');
    }

    /**
     * Show renewal approvals page (PSG Council Adviser approval)
     */
    public function renewalApprovals()
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Get renewals ready for PSG Council approval (parallel approval system)
        $renewals = ClubRenewal::where('status', 'pending_admin')
            ->where('prepared_by_president', true)
            ->where('certified_by_adviser', true)
            ->where('reviewed_by_psg', false) // Only show renewals PSG hasn't approved yet
            ->orderBy('created_at', 'desc')
            ->get();

        return view('psg-council.renewal-approvals', compact('renewals'));
    }

    /**
     * Show specific renewal approval details
     */
    public function showRenewalApproval(ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Check if renewal is ready for admin approval
        if (!($renewal->prepared_by_president && $renewal->certified_by_adviser)) {
            return redirect()->route('psg-council.renewal-approvals')
                ->with('error', 'This renewal is not ready for admin approval.');
        }

        return view('psg-council.renewal-approval-details', compact('renewal'));
    }

    /**
     * Approve renewal (PSG Council Adviser's approval - Parallel System)
     */
    public function noteRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate password
        $request->validate([
            'current_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!$admin) {
            return back()->withErrors(['current_password' => 'Session expired. Please login again.']);
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        // Check if renewal is in correct state for approval
        if (!($renewal->prepared_by_president && $renewal->certified_by_adviser)) {
            return back()->withErrors(['error' => 'This renewal is not ready for admin approval.']);
        }

        if ($renewal->reviewed_by_psg) {
            return back()->withErrors(['error' => 'You have already approved this renewal.']);
        }

        // Update renewal with PSG Council approval
        $renewal->update([
            'reviewed_by_psg' => true,
            'reviewed_by_psg_at' => now(),
            'reviewed_by_psg_user' => $admin->name ?? 'PSG Council Adviser',
        ]);

        // Check if all admin approvals are complete (parallel approval system)
        $this->checkAndCompleteRenewal($renewal);

        return redirect()->route('psg-council.renewal-approvals')
            ->with('success', 'Renewal approved successfully! ' . $this->getRemainingApprovals($renewal));
    }

    /**
     * Approve renewal (PSG Council Adviser's final approval)
     */
    public function approveRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate password
        $request->validate([
            'current_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!$admin) {
            return back()->withErrors(['current_password' => 'Session expired. Please login again.']);
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        // Check if renewal is in correct state for PSG approval
        if (!($renewal->prepared_by_president && $renewal->certified_by_adviser)) {
            return back()->withErrors(['error' => 'This renewal is not ready for PSG Council review.']);
        }

        if ($renewal->reviewed_by_psg) {
            return back()->withErrors(['error' => 'This renewal has already been reviewed by PSG Council.']);
        }

        // PSG Council Adviser marks the renewal as reviewed (admin-level step continues next)
        $updated = $renewal->update([
            'reviewed_by_psg' => true,
            'reviewed_by_psg_at' => now(),
            'reviewed_by_psg_user' => $admin->name ?? 'PSG Council Adviser',
        ]);

        if (!$updated) {
            return back()->withErrors(['error' => 'Failed to update renewal status. Please try again.']);
        }

        // Force refresh the model to verify the update
        $renewal->refresh();
        if (!$renewal->reviewed_by_psg) {
            return back()->withErrors(['error' => 'Database update failed. Please contact technical support.']);
        }

        return redirect()->route('psg-council.renewal-approvals')
            ->with('success', 'Renewal approved successfully! It will now proceed to Dean for noting.');
    }

    /**
     * Reject renewal (PSG Council Adviser's rejection)
     */
    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate password and rejection reason
        $request->validate([
            'current_password' => 'required|string',
            'rejection_reason' => 'required|string|max:1000',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        // Update renewal with rejection
        $renewal->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('psg-council.renewal-approvals')
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
            
            // All approvals complete - mark as fully approved
            $renewal->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
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

    public function viewDocument(ClubRegistrationRequest $registration, $type)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $filePath = match($type) {
            'constitution' => $registration->constitution_file,
            'officers' => $registration->officers_list_file,
            'activities' => $registration->activities_plan_file,
            'budget' => $registration->budget_proposal_file,
            default => null
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function reject(Request $request, ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'psg_council_adviser') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Validate the rejection reason and password
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'current_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        // Update the registration status
        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'rejected_at' => now(),
        ]);

        // Log the rejection
        \Log::info("Club registration rejected by PSG Council Adviser", [
            'registration_id' => $registration->id,
            'club_name' => $registration->club_name,
            'officer_name' => $registration->officer->name,
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'timestamp' => now()
        ]);

        return redirect()->route('psg-council.approvals')
            ->with('success', 'Registration rejected successfully.');
    }
}
