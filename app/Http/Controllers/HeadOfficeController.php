<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use App\Models\Notification;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HeadOfficeController extends Controller
{
    public function dashboard()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Dashboard statistics
        $totalOrganizations = Club::count();
        $activeOrganizations = Club::where('status', 'active')->count();
        $suspendedOrganizations = Club::where('status', 'suspended')->count();
        $pendingRenewalOrganizations = Club::where('status', 'pending_renewal')->count();
        $totalMembers = Club::sum('member_count');

        // Registration statistics
        $newRegistrations = ClubRegistrationRequest::where('status', 'pending')->count();
        $pendingVerifications = ClubRegistrationRequest::where('status', 'pending')
            ->where('verified_by_osa', false)->count();
        $verifiedApplications = ClubRegistrationRequest::where('verified_by_osa', true)->count();

        // Organization types breakdown
        $academicClubs = Club::where('club_type', 'Academic')->count();
        $interestClubs = Club::where('club_type', 'Interest')->count();

        // Notification statistics
        $totalNotifications = Notification::count();
        $pendingNotifications = Notification::where('is_read', false)->count();
        $renewalReminders = Notification::where('type', 'renewal_reminder')->where('is_read', false)->count();

        // Recent activities (last 10 activities)
        $recentActivities = collect();

        // Recent registrations
        $recentRegistrations = ClubRegistrationRequest::with('officer')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentRegistrations as $registration) {
            $recentActivities->push([
                'type' => 'registration',
                'title' => $registration->club_name . ' registered',
                'description' => 'New club registration from ' . $registration->department,
                'time' => $registration->created_at,
                'icon' => 'plus',
                'color' => 'green'
            ]);
        }

        // Recent club updates (recently updated clubs)
        $recentClubUpdates = Club::orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentClubUpdates as $club) {
            $recentActivities->push([
                'type' => 'update',
                'title' => $club->name . ' updated',
                'description' => 'Organization information updated',
                'time' => $club->updated_at,
                'icon' => 'edit',
                'color' => 'blue'
            ]);
        }

        // Sort activities by time (most recent first)
        $recentActivities = $recentActivities->sortByDesc('time')->take(5);

        return view('head-office.dashboard', compact(
            'totalOrganizations',
            'activeOrganizations',
            'suspendedOrganizations',
            'pendingRenewalOrganizations',
            'totalMembers',
            'newRegistrations',
            'pendingVerifications',
            'verifiedApplications',
            'academicClubs',
            'interestClubs',
            'totalNotifications',
            'pendingNotifications',
            'renewalReminders',
            'recentActivities'
        ));
    }

    public function organizations(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

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

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('head-office.organizations', compact('clubs', 'clubTypes', 'departments', 'statuses'));
    }

    public function showOrganization(Club $club)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
        
        $club->load(['clubUsers']);
        return view('head-office.organization-details', compact('club'));
    }

    public function approvals(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
        
        // Get status filter from request, default to 'pending'
        $status = $request->get('status', 'pending');
        
        // Build query based on status
        $query = ClubRegistrationRequest::with('officer');
        
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Get counts for tabs
        $pendingCount = ClubRegistrationRequest::where('status', 'pending')->count();
        $approvedCount = ClubRegistrationRequest::where('status', 'approved')->count();
        $rejectedCount = ClubRegistrationRequest::where('status', 'rejected')->count();

        return view('head-office.approvals', compact('registrations', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function showApproval(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
        
        $registration->load('officer');
        return view('head-office.approval-details', compact('registration'));
    }

    public function verify(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $registration->update([
            'verified_by_osa' => true,
            'verified_by_osa_at' => now(),
            'verified_by_osa_user' => session('user.name'),
        ]);

        return redirect()->route('head-office.approvals')
            ->with('success', 'Registration verified successfully!');
    }

    public function reject(ClubRegistrationRequest $registration, Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => session('user')->name ?? 'Unknown Admin',
            'rejected_at' => now(),
        ]);

        return redirect()->route('head-office.approvals')
            ->with('success', 'Registration rejected successfully!');
    }

    public function suspendOrganization(Club $club)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $previousStatus = $club->status;
        $adminName = session('user')->name ?? 'Unknown Admin';

        $club->update([
            'status' => 'suspended',
            'updated_at' => now()
        ]);

        // Log the action for audit trail
        \Log::info("Organization suspended", [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'previous_status' => $previousStatus,
            'new_status' => 'suspended',
            'admin_name' => $adminName,
            'admin_role' => 'head_student_affairs',
            'timestamp' => now()
        ]);

        return redirect()->route('head-office.organizations')
            ->with('success', "Organization '{$club->name}' has been suspended successfully!");
    }

    public function activateOrganization(Club $club)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $previousStatus = $club->status;
        $adminName = session('user')->name ?? 'Unknown Admin';

        $club->update([
            'status' => 'active',
            'updated_at' => now()
        ]);

        // Log the action for audit trail
        \Log::info("Organization activated", [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'previous_status' => $previousStatus,
            'new_status' => 'active',
            'admin_name' => $adminName,
            'admin_role' => 'head_student_affairs',
            'timestamp' => now()
        ]);

        return redirect()->route('head-office.organizations')
            ->with('success', "Organization '{$club->name}' has been activated successfully!");
    }

    /**
     * Show renewals overview page
     */
    public function renewals()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Get all renewals with their status
        $renewals = ClubRenewal::with('club')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalRenewals = $renewals->count();
        $pendingRenewals = $renewals->where('status', 'pending_admin')->count();
        $approvedRenewals = $renewals->where('status', 'approved')->count();
        $rejectedRenewals = $renewals->where('status', 'rejected')->count();

        return view('head-office.renewals', compact(
            'renewals',
            'totalRenewals',
            'pendingRenewals',
            'approvedRenewals',
            'rejectedRenewals'
        ));
    }

    /**
     * Clear all pending notifications sent by the head office to clubs
     */
    public function clearPendingNotifications(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        try {
            // Get count of pending notifications before deletion
            $pendingCount = Notification::where('is_read', false)->count();
            
            // Delete all unread notifications
            $deletedCount = Notification::where('is_read', false)->delete();
            
            // Log the action for audit trail
            \Log::info("Pending notifications cleared by head office", [
                'admin_name' => session('user')->name ?? 'Unknown Admin',
                'admin_role' => 'head_student_affairs',
                'notifications_cleared' => $deletedCount,
                'timestamp' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully cleared {$deletedCount} pending notifications.",
                    'cleared_count' => $deletedCount
                ]);
            }

            return redirect()->back()->with('success', "Successfully cleared {$deletedCount} pending notifications sent to clubs.");
            
        } catch (\Exception $e) {
            \Log::error("Error clearing pending notifications", [
                'error' => $e->getMessage(),
                'admin_name' => session('user')->name ?? 'Unknown Admin',
                'timestamp' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while clearing notifications.'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while clearing notifications.');
        }
    }

    /**
     * Clear specific type of notifications
     */
    public function clearNotificationsByType(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $request->validate([
            'type' => 'required|string|in:renewal_reminder,general,all'
        ]);

        try {
            $query = Notification::where('is_read', false);
            
            if ($request->type !== 'all') {
                $query->where('type', $request->type);
            }
            
            $deletedCount = $query->delete();
            
            // Log the action for audit trail
            \Log::info("Notifications cleared by type", [
                'admin_name' => session('user')->name ?? 'Unknown Admin',
                'admin_role' => 'head_student_affairs',
                'notification_type' => $request->type,
                'notifications_cleared' => $deletedCount,
                'timestamp' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully cleared {$deletedCount} pending {$request->type} notifications.",
                    'cleared_count' => $deletedCount
                ]);
            }

            return redirect()->back()->with('success', "Successfully cleared {$deletedCount} pending {$request->type} notifications.");
            
        } catch (\Exception $e) {
            \Log::error("Error clearing notifications by type", [
                'error' => $e->getMessage(),
                'notification_type' => $request->type,
                'admin_name' => session('user')->name ?? 'Unknown Admin',
                'timestamp' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while clearing notifications.'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while clearing notifications.');
        }
    }

    public function viewDocument(ClubRegistrationRequest $registration, $type)
    {
        if (session('admin_role') !== 'head_student_affairs') {
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

    public function decisionSupport()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $clubs = Club::with(['violations' => function($query) {
            $query->orderBy('violation_date', 'desc');
        }])->get();

        // Calculate risk metrics for each club
        $clubsWithRisk = $clubs->map(function ($club) {
            $totalPoints = $club->violations->where('status', 'confirmed')->sum('points');
            $violationsCount = $club->violations->where('status', 'confirmed')->count();
            $recentViolations = $club->violations->where('violation_date', '>=', now()->subMonths(6))->count();
            
            // Risk calculation logic
            $riskScore = $totalPoints;
            if ($recentViolations > 2) $riskScore += 20;
            if ($violationsCount > 5) $riskScore += 15;
            
            $riskLevel = 'low';
            if ($riskScore >= 100) $riskLevel = 'critical';
            elseif ($riskScore >= 50) $riskLevel = 'high';
            elseif ($riskScore >= 20) $riskLevel = 'medium';
            elseif ($riskScore > 0) $riskLevel = 'low';
            else $riskLevel = 'none';

            // Recommendation based on risk
            $recommendation = match($riskLevel) {
                'critical' => 'Immediate suspension recommended. Schedule hearing.',
                'high' => 'Final warning required. Close monitoring needed.',
                'medium' => 'Formal warning. Require improvement plan.',
                'low' => 'Verbal warning. Monitor behavior.',
                'none' => 'No action required. Club in good standing.'
            };

            $club->risk_score = $riskScore;
            $club->risk_level = $riskLevel;
            $club->recommendation = $recommendation;
            $club->violations_count = $violationsCount;
            $club->recent_violations = $recentViolations;

            return $club;
        });

        // Sort by risk score descending
        $clubsWithRisk = $clubsWithRisk->sortByDesc('risk_score');

        return view('head-office.decision-support', compact('clubsWithRisk'));
    }

    public function clubViolationDetails($clubId)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $club = Club::with(['violations' => function($query) {
            $query->with(['appeals' => function($appealQuery) {
                $appealQuery->orderBy('submitted_at', 'desc');
            }])->orderBy('violation_date', 'desc');
        }, 'clubUsers'])->findOrFail($clubId);

        return view('head-office.club-violation-details', compact('club'));
    }

    public function suspendClubWithAuthentication(Request $request, $clubId)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $request->validate([
            'admin_password' => 'required',
            'violation_reason' => 'required|string|max:500',
            'severity' => 'required|in:minor,moderate,major,critical'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->admin_password, $admin->password)) {
            return response()->json(['error' => 'Invalid password. Please try again.'], 422);
        }

        $club = Club::findOrFail($clubId);
        $previousStatus = $club->status;
        $adminName = $admin->name ?? 'Unknown Admin';

        // Update club status to suspended
        $club->update([
            'status' => 'suspended',
            'updated_at' => now()
        ]);

        // Log the action for audit trail
        \Log::info("Organization suspended via Decision Support System", [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'previous_status' => $previousStatus,
            'new_status' => 'suspended',
            'suspension_reason' => $request->violation_reason,
            'admin_name' => $adminName,
            'admin_role' => 'head_student_affairs',
            'timestamp' => now(),
            'source' => 'decision_support_system'
        ]);

        return response()->json([
            'success' => true,
            'message' => "Club '{$club->name}' has been suspended successfully.",
            'redirect' => route('head-office.decision-support')
        ]);
    }

    public function reactivateClubWithAuthentication(Request $request, $clubId)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $request->validate([
            'admin_password' => 'required',
            'reactivation_reason' => 'required|string|max:500'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!Hash::check($request->admin_password, $admin->password)) {
            return response()->json(['error' => 'Invalid password. Please try again.'], 422);
        }

        $club = Club::findOrFail($clubId);
        $previousStatus = $club->status;
        $adminName = $admin->name ?? 'Unknown Admin';

        // Update club status to active
        $club->update([
            'status' => 'active',
            'updated_at' => now()
        ]);

        // Log the action for audit trail
        \Log::info("Organization reactivated via Decision Support System", [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'previous_status' => $previousStatus,
            'new_status' => 'active',
            'reactivation_reason' => $request->reactivation_reason,
            'admin_name' => $adminName,
            'admin_role' => 'head_student_affairs',
            'timestamp' => now(),
            'source' => 'decision_support_system'
        ]);

        return response()->json([
            'success' => true,
            'message' => "Club '{$club->name}' has been reactivated successfully.",
            'redirect' => route('head-office.decision-support')
        ]);
    }
}