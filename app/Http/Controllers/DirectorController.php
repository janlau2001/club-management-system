<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DirectorController extends Controller
{
    public function dashboard(Request $request)
    {
        if (session('admin_role') !== 'director_student_affairs') {
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
            ->where('approved_by_psg_council', true)
            ->where('noted_by_director', false)->count();
        $notedByMe = ClubRegistrationRequest::where('noted_by_director', true)->count();

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('director.dashboard', compact(
            'clubs',
            'totalOrganizations',
            'activeOrganizations',
            'pendingApprovals',
            'notedByMe',
            'clubTypes',
            'departments',
            'statuses'
        ));
    }



    public function showOrganization(Club $club)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }
        
        $club->load(['clubUsers']);
        return view('director.organization-details', compact('club'));
    }

    public function exportOrganizationPdf(Club $club)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        $club->load(['officers', 'members', 'advisers']);

        $data = [
            'club'          => $club,
            'generatedDate' => now()->format('F j, Y'),
            'generatedTime' => now()->format('g:i A'),
            'adminName'     => session('user')->name ?? 'Administrator',
            'adminRole'     => 'Director of Student Affairs',
        ];

        $pdf = Pdf::loadView('reports.single-club-report', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'club-report-' . str_replace(' ', '-', strtolower($club->name)) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function approvals(Request $request)
    {
        if (session('admin_role') !== 'director_student_affairs') {
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
            // Show registrations that Director has noted
            $query->where('noted_by_director', true);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Get counts for tabs
        $pendingCount = ClubRegistrationRequest::where('status', 'pending')->count();
        $approvedCount = ClubRegistrationRequest::where('noted_by_director', true)->count();

        return view('director.approvals', compact('registrations', 'status', 'pendingCount', 'approvedCount'));
    }

    public function showApproval(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }
        
        $registration->load('officer');
        return view('director.approval-details', compact('registration'));
    }

    public function approve(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Validate password
        request()->validate([
            'current_password' => 'required|string',
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check(request('current_password'), $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }
        
        $registration->update([
            'noted_by_director' => true,
            'noted_by_director_at' => now(),
            'noted_by_director_user' => session('user.name'),
            'current_approval_step' => 'vp',
        ]);

        return redirect()->route('director.approvals')
            ->with('success', 'Registration noted successfully!');
    }

    public function reject(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
        }

        // Validate password and rejection reason
        request()->validate([
            'current_password' => 'required|string',
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check(request('current_password'), $admin->password)) {
            return back()->withErrors(['current_password' => 'Invalid password.']);
        }

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => request('rejection_reason'),
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'rejected_at' => now(),
        ]);

        return redirect()->route('director.approvals')
            ->with('success', 'Registration rejected successfully!');
    }

    public function downloadDocument(ClubRegistrationRequest $registration, $type)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('dashboard.index')->with('error', 'Access denied.');
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

    /**
     * Show renewal approvals page (Director approval - Parallel System)
     */
    public function renewalApprovals()
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Mark last visit for sidebar badge tracking
        session(['sidebar_last_visited.renewals' => now()]);

        // Get renewals ready for Director approval (parallel approval system)
        $renewals = ClubRenewal::with('club')
            ->where('status', 'pending_admin')
            ->where('prepared_by_president', true)
            ->where('certified_by_adviser', true)
            ->where('endorsed_by_osa', false) // Only show renewals Director hasn't approved yet
            ->orderBy('created_at', 'desc')
            ->get();

        return view('director.renewal-approvals', compact('renewals'));
    }

    /**
     * Show specific renewal approval details
     */
    public function showRenewalApproval(ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'director_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        if (!$renewal->isReadyForAdminApproval()) {
            return redirect()->route('director.renewal-approvals')
                ->with('error', 'This renewal is not ready for admin approval.');
        }

        return view('director.renewal-approval-details', compact('renewal'));
    }

    /**
     * Endorse renewal (Director's action - second step in admin approval)
     */
    public function endorseRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'director_student_affairs') {
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

        // Director endorses the renewal (parallel admin approval)
        $renewal->update([
            'endorsed_by_osa' => true,
            'endorsed_by_osa_at' => now(),
            'endorsed_by_osa_user' => (session('user')?->name) ?? 'Unknown Admin',
        ]);

        // Check if all admin approvals are complete (parallel approval system)
        $this->checkAndCompleteRenewal($renewal);

        return redirect()->route('director.renewal-approvals')
            ->with('success', 'Renewal endorsed successfully! ' . $this->getRemainingApprovals($renewal));
    }

    /**
     * Reject renewal (Director's rejection)
     */
    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        if (session('admin_role') !== 'director_student_affairs') {
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

        return redirect()->route('director.renewal-approvals')
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
}