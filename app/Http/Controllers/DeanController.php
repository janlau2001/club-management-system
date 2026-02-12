<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DeanController extends Controller
{
    public function dashboard(Request $request)
    {
        if (session('admin_role') !== 'dean') {
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
        $pendingApprovals = ClubRegistrationRequest::where('status', 'pending')
            ->where('endorsed_by_dean', false)->count();
        $endorsedByMe = ClubRegistrationRequest::where('endorsed_by_dean', true)->count();

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('dean.dashboard', compact(
            'clubs',
            'totalOrganizations',
            'activeOrganizations',
            'pendingApprovals',
            'endorsedByMe',
            'clubTypes',
            'departments',
            'statuses'
        ));
    }



    public function showOrganization(Club $club)
    {
        if (session('admin_role') !== 'dean') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $club->load(['clubUsers']);
        return view('dean.organization-details', compact('club'));
    }

    public function approvals(Request $request)
    {
        if (session('admin_role') !== 'dean') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Mark last visit for sidebar badge tracking
        session(['sidebar_last_visited.approvals' => now()]);

        // Get status filter from request, default to 'pending'
        $status = $request->get('status', 'pending');
        
        // Build query based on status
        $query = ClubRegistrationRequest::with('officer');
        
        if ($status === 'pending') {
            // Show all pending registrations (can see but may not approve yet)
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            // Show registrations that Dean has endorsed
            $query->where('endorsed_by_dean', true);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Get counts for tabs
        $pendingCount = ClubRegistrationRequest::where('status', 'pending')->count();
        $approvedCount = ClubRegistrationRequest::where('endorsed_by_dean', true)->count();

        return view('dean.approvals', compact('registrations', 'status', 'pendingCount', 'approvedCount'));
    }

    public function showApproval(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'dean') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $registration->load('officer');
        return view('dean.approval-details', compact('registration'));
    }

    public function approve(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'dean') {
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

        // Check if already endorsed by Dean
        if ($registration->endorsed_by_dean) {
            return redirect()->route('dean.approvals')
                ->with('error', 'Registration has already been endorsed by Dean.');
        }

        $registration->update([
            'endorsed_by_dean' => true,
            'endorsed_by_dean_at' => now(),
            'endorsed_by_dean_user' => (session('user')?->name ?? 'Dean'),
            'current_approval_step' => 'psg_council', // Move to next step
        ]);

        return redirect()->route('dean.approvals')
            ->with('success', 'Registration endorsed by Dean successfully! It will now proceed to PSG Council for approval.');
    }

    /**
     * Show renewal approvals page (Dean approval - Parallel System)
     */
    public function renewalApprovals()
    {
        if (session('admin_role') !== 'dean') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Mark last visit for sidebar badge tracking
        session(['sidebar_last_visited.renewals' => now()]);

        // Get renewals ready for Dean approval (parallel approval system)
        $renewals = ClubRenewal::with('club')
            ->where('status', 'pending_admin')
            ->where('prepared_by_president', true)
            ->where('certified_by_adviser', true)
            ->where('noted_by_dean', false) // Only show renewals Dean hasn't approved yet
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dean.renewal-approvals', compact('renewals'));
    }

    /**
     * Show specific renewal approval details
     */
    public function showRenewalApproval(ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'dean') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        if (!$renewal->isReadyForAdminApproval()) {
            return redirect()->route('dean.renewal-approvals')
                ->with('error', 'This renewal is not ready for admin approval.');
        }

        return view('dean.renewal-approval-details', compact('renewal'));
    }

    /**
     * Note renewal (Dean's action - first step in admin approval)
     */
    public function noteRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'dean') {
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

        // Dean notes the renewal (admin-level step begins)
        $renewal->update([
            'noted_by_dean' => true,
            'noted_by_dean_at' => now(),
            'noted_by_dean_user' => (session('user')?->name ?? 'Dean'),
        ]);

        // Check if all admin approvals are complete (parallel approval system)
        $this->checkAndCompleteRenewal($renewal);

        return redirect()->route('dean.renewal-approvals')
            ->with('success', 'Renewal noted successfully! ' . $this->getRemainingApprovals($renewal));
    }

    /**
     * Reject renewal (Dean's rejection)
     */
    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'dean') {
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

        return redirect()->route('dean.renewal-approvals')
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
        if (session('admin_role') !== 'dean') {
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
        if (session('admin_role') !== 'dean') {
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

        // Explicit safeguard: Verify the registration and officer still exist
        if (!$registration->exists || !$registration->officer) {
            \Log::critical("CRITICAL: Registration or officer was deleted during rejection!", [
                'registration_id' => $registration->id,
                'club_name' => $registration->club_name,
                'admin' => session('user')->name ?? 'Unknown Admin',
                'action' => 'dean_rejection'
            ]);
        }

        // Log the rejection
        \Log::info("Club registration rejected by Dean", [
            'registration_id' => $registration->id,
            'club_name' => $registration->club_name,
            'officer_name' => $registration->officer->name,
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'timestamp' => now()
        ]);

        return redirect()->route('dean.approvals')
            ->with('success', 'Registration rejected successfully.');
    }
}

