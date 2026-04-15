<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use App\Models\ClubUser;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $totalOrganizations = Club::count();
        $newRegistrations = ClubRegistrationRequest::where('status', 'pending')->count();
        $pendingRenewals = Club::where('status', 'pending_renewal')->count();
        $totalMembers = Club::sum('member_count');

        // Organization types breakdown
        $academicClubs = Club::where('club_type', 'Academic')->count();
        $interestClubs = Club::where('club_type', 'Interest')->count();

        // Recent activities
        $recentActivities = collect();

        // Recent approved registrations
        $approvedRegistrations = ClubRegistrationRequest::with('officer')
            ->where('verified_by_osa', true)
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        foreach ($approvedRegistrations as $registration) {
            $recentActivities->push([
                'type' => 'approved',
                'title' => $registration->club_name . ' approved',
                'description' => 'Registration approved ' . $registration->updated_at->diffForHumans(),
                'time' => $registration->updated_at,
                'color' => 'green'
            ]);
        }

        // Recent pending registrations
        $pendingRegistrations = ClubRegistrationRequest::with('officer')
            ->where('status', 'pending')
            ->where('verified_by_osa', false)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($pendingRegistrations as $registration) {
            $recentActivities->push([
                'type' => 'registration',
                'title' => $registration->club_name . ' registered',
                'description' => 'Applied ' . $registration->created_at->diffForHumans(),
                'time' => $registration->created_at,
                'color' => 'blue'
            ]);
        }

        // Recent suspended clubs
        $suspendedClubs = Club::where('status', 'suspended')
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        foreach ($suspendedClubs as $club) {
            $recentActivities->push([
                'type' => 'suspended',
                'title' => $club->name . ' suspended',
                'description' => 'Club suspended ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'red'
            ]);
        }

        // Recent active clubs (recently resumed)
        $activeClubs = Club::where('status', 'active')
            ->where('updated_at', '>', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        foreach ($activeClubs as $club) {
            $recentActivities->push([
                'type' => 'resumed',
                'title' => $club->name . ' resumed',
                'description' => 'Club status changed to active ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'green'
            ]);
        }

        // Recent renewal pending clubs
        $renewalPendingClubs = Club::where('status', 'pending_renewal')
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        foreach ($renewalPendingClubs as $club) {
            $recentActivities->push([
                'type' => 'renewal_pending',
                'title' => $club->name . ' renewal pending',
                'description' => 'Club renewal required ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'yellow'
            ]);
        }

        // Sort activities by time (most recent first)
        $recentActivities = $recentActivities->sortByDesc('time')->take(10);

        return view('dashboard.index', compact(
            'totalOrganizations',
            'newRegistrations',
            'pendingRenewals',
            'totalMembers',
            'academicClubs',
            'interestClubs',
            'recentActivities'
        ));
    }

    public function activities()
    {
        // Get all activities from the last 24 hours
        $yesterday = now()->subHours(24);
        $allActivities = collect();

        // Approved registrations (last 24 hours)
        $approvedRegistrations = ClubRegistrationRequest::with('officer')
            ->where('verified_by_osa', true)
            ->where('updated_at', '>', $yesterday)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($approvedRegistrations as $registration) {
            $allActivities->push([
                'type' => 'approved',
                'title' => $registration->club_name . ' approved',
                'description' => 'Registration approved by ' . (session('admin_role') === 'head_student_affairs' ? 'SAASS' : 'Admin') . ' • ' . $registration->updated_at->diffForHumans(),
                'time' => $registration->updated_at,
                'color' => 'green',
                'details' => [
                    'club_name' => $registration->club_name,
                    'officer' => $registration->officer->name,
                    'department' => $registration->department
                ]
            ]);
        }

        // Pending registrations (last 24 hours)
        $pendingRegistrations = ClubRegistrationRequest::with('officer')
            ->where('status', 'pending')
            ->where('verified_by_osa', false)
            ->where('created_at', '>', $yesterday)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingRegistrations as $registration) {
            $allActivities->push([
                'type' => 'registration',
                'title' => $registration->club_name . ' registered',
                'description' => 'New registration application • ' . $registration->created_at->diffForHumans(),
                'time' => $registration->created_at,
                'color' => 'blue',
                'details' => [
                    'club_name' => $registration->club_name,
                    'officer' => $registration->officer->name,
                    'department' => $registration->department
                ]
            ]);
        }

        // Suspended clubs (last 24 hours)
        $suspendedClubs = Club::where('status', 'suspended')
            ->where('updated_at', '>', $yesterday)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($suspendedClubs as $club) {
            $allActivities->push([
                'type' => 'suspended',
                'title' => $club->name . ' suspended',
                'description' => 'Club status changed to suspended • ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'red',
                'details' => [
                    'club_name' => $club->name,
                    'department' => $club->department,
                    'member_count' => $club->member_count
                ]
            ]);
        }

        // Recently resumed clubs (last 24 hours)
        $activeClubs = Club::where('status', 'active')
            ->where('updated_at', '>', $yesterday)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($activeClubs as $club) {
            $allActivities->push([
                'type' => 'resumed',
                'title' => $club->name . ' resumed',
                'description' => 'Club status changed to active • ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'green',
                'details' => [
                    'club_name' => $club->name,
                    'department' => $club->department,
                    'member_count' => $club->member_count
                ]
            ]);
        }

        // Renewal pending clubs (last 24 hours)
        $renewalPendingClubs = Club::where('status', 'pending_renewal')
            ->where('updated_at', '>', $yesterday)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($renewalPendingClubs as $club) {
            $allActivities->push([
                'type' => 'renewal_pending',
                'title' => $club->name . ' renewal pending',
                'description' => 'Club renewal required • ' . $club->updated_at->diffForHumans(),
                'time' => $club->updated_at,
                'color' => 'yellow',
                'details' => [
                    'club_name' => $club->name,
                    'department' => $club->department,
                    'member_count' => $club->member_count
                ]
            ]);
        }

        // Sort all activities by time (most recent first)
        $allActivities = $allActivities->sortByDesc('time');

        return view('dashboard.activities', compact('allActivities'));
    }

    public function organizations(Request $request)
    {
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

        return view('dashboard.organizations', compact('clubs', 'clubTypes', 'departments', 'statuses'));
    }

    public function showOrganization(Club $club)
    {
        $club->load(['clubUsers']);
        return view('dashboard.organization-details', compact('club'));
    }

    public function registrations()
    {
        $registrations = ClubRegistrationRequest::with('officer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.registrations', compact('registrations'));
    }

    public function showRegistration(ClubRegistrationRequest $registration)
    {
        $registration->load('officer');
        return view('dashboard.registration-details', compact('registration'));
    }

    public function approveRegistration(ClubRegistrationRequest $registration)
    {
        // Check if all four approvals are complete
        if (!$registration->verified_by_osa || !$registration->noted_by_director || !$registration->approved_by_vp || !$registration->endorsed_by_dean) {
            return redirect()->route('dashboard.registrations.show', $registration)
                ->with('error', 'All four approvals (Head Office, Director, VP, Dean) must be completed before final approval.');
        }

        DB::transaction(function () use ($registration) {
            // Create the club
            $club = Club::create([
                'name' => $registration->club_name,
                'department' => $registration->department,
                'club_type' => $registration->nature,
                'description' => $registration->rationale,
                'adviser_name' => $registration->recommended_adviser,
                'adviser_email' => '',
                'date_registered' => now(),
                'member_count' => 1, // Start with 1 member (the officer)
                'status' => 'active',
            ]);

            // Create the officer as a club user so they can login
            $officer = $registration->officer;
            ClubUser::create([
                'club_id' => $club->id,
                'name' => $officer->name,
                'email' => $officer->email,
                'password' => $officer->password, // Use the same password they registered with
                'student_id' => $officer->student_id,
                'role' => 'officer',
                'position' => $officer->position,
                'department' => $officer->department,
                'year_level' => $officer->year_level,
                'joined_date' => now(),
            ]);

            // Update officer status
            $officer->update([
                'club_status' => 'active',
                'current_club' => $club->name,
                'registration_status' => 'approved',
            ]);

            // Update registration status
            $registration->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('dashboard.registrations')
            ->with('success', 'Club registration approved successfully!');
    }

    public function rejectRegistration(Request $request, ClubRegistrationRequest $registration)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        return redirect()->route('dashboard.registrations')
            ->with('success', 'Club registration rejected.');
    }

    public function renewals(Request $request)
    {
        // Get clubs and calculate their renewal status based on date_registered
        $clubsQuery = Club::with(['clubUsers'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->department, function($query, $department) {
                $query->where('department', $department);
            })
            ->when($request->renewal_status, function($query, $status) {
                // Note: We'll filter these after getting the clubs using the Club model methods
                // since the filtering logic now depends on computed properties
            });

        $clubs = $clubsQuery->orderBy('date_registered', 'asc')->get();

        // Calculate renewal information for each club
        $renewalData = $clubs->map(function($club) {
            $now = now();
            
            // Use the new Club model methods
            $daysUntilDue = $club->days_until_renewal;
            $renewalDueDate = $club->renewal_due_date;
            
            // Determine renewal status
            if ($daysUntilDue > 30) {
                $renewalStatus = 'not_due';
                $statusLabel = 'Not Due Yet';
                $statusBadge = 'bg-gray-100 text-gray-800';
            } elseif ($daysUntilDue > 0 && $daysUntilDue <= 30) {
                $renewalStatus = 'upcoming';
                $statusLabel = "Due in {$daysUntilDue} days";
                $statusBadge = 'bg-yellow-100 text-yellow-800';
            } elseif ($daysUntilDue >= -30 && $daysUntilDue <= 0) {
                $renewalStatus = 'due';
                $statusLabel = $daysUntilDue === 0 ? 'Due Today' : 'Due ' . abs($daysUntilDue) . ' days ago';
                $statusBadge = 'bg-orange-100 text-orange-800';
            } else {
                $renewalStatus = 'overdue';
                $statusLabel = 'Overdue by ' . abs($daysUntilDue) . ' days';
                $statusBadge = 'bg-red-100 text-red-800';
            }

            // Check if club has submitted a renewal request
            $submittedRenewal = ClubRenewal::where('club_id', $club->id)
                ->whereYear('created_at', now()->year)
                ->latest()
                ->first();

            // If the club was recently renewed (approved renewal exists), override the status
            if ($submittedRenewal && $submittedRenewal->status === 'approved' && $club->last_renewal_date) {
                $renewalStatus = 'not_due';
                $statusLabel = 'Not Due Yet';
                $statusBadge = 'bg-gray-100 text-gray-800';
            }

            // Determine submission status with updated logic
            $submissionStatus = 'Not Submitted';
            if ($submittedRenewal) {
                switch ($submittedRenewal->status) {
                    case 'approved':
                        $submissionStatus = 'Renewed';
                        break;
                    case 'rejected':
                        $submissionStatus = 'Rejected';
                        break;
                    case 'pending_admin':
                        $submissionStatus = 'Pending Admin Review';
                        break;
                    case 'pending_internal':
                        $submissionStatus = 'Pending Internal Approval';
                        break;
                    default:
                        $submissionStatus = $submittedRenewal->status_label;
                        break;
                }
            }

            return (object) [
                'id' => $club->id,
                'club' => $club,
                'club_name' => $club->name,
                'department' => $club->department,
                'date_registered' => $club->date_registered,
                'last_renewal_date' => $club->last_renewal_date,
                'renewal_due_date' => $renewalDueDate,
                'days_until_due' => $daysUntilDue,
                'renewal_status' => $renewalStatus,
                'status_label' => $statusLabel,
                'status_badge' => $statusBadge,
                'submitted_renewal' => $submittedRenewal,
                'has_submitted' => $submittedRenewal !== null,
                'submission_status' => $submissionStatus,
                'member_count' => $club->clubUsers()->count(),
            ];
        });

        // Filter by renewal status if specified
        if ($request->renewal_status) {
            $renewalData = $renewalData->filter(function($item) use ($request) {
                switch($request->renewal_status) {
                    case 'upcoming':
                        return $item->renewal_status === 'upcoming';
                    case 'due':
                        return $item->renewal_status === 'due';
                    case 'overdue':
                        return $item->renewal_status === 'overdue';
                    case 'recent':
                        // Clubs renewed in the last 6 months
                        return $item->club->last_renewal_date && 
                               $item->club->last_renewal_date->diffInMonths(now()) <= 6;
                    default:
                        return $item->renewal_status === $request->renewal_status;
                }
            });
        }

        // Calculate statistics using the new Club model methods
        $now = now();
        $allClubs = Club::all();
        
        $upcomingRenewals = $allClubs->filter(function($club) {
            return $club->isRenewalDueSoon();
        })->count();

        $dueRenewals = $allClubs->filter(function($club) {
            $daysUntil = $club->days_until_renewal;
            return $daysUntil >= -30 && $daysUntil <= 0;
        })->count();

        $overdueRenewals = $allClubs->filter(function($club) {
            return $club->isRenewalOverdue() && $club->days_until_renewal < -30;
        })->count();

        $submittedRenewals = ClubRenewal::whereYear('created_at', now()->year)
            ->whereIn('status', ['pending_internal', 'pending_admin', 'approved'])
            ->count();

        return view('dashboard.renewals', compact(
            'renewalData',
            'upcomingRenewals',
            'dueRenewals', 
            'overdueRenewals',
            'submittedRenewals'
        ));
    }

    public function showRenewal($renewalId)
    {
        $renewal = ClubRenewal::with('club')->findOrFail($renewalId);
        return view('dashboard.renewal-details', compact('renewal'));
    }

    public function approveRenewal(Request $request, ClubRenewal $renewal)
    {
        // Validate password
        $request->validate([
            'password' => 'required|string'
        ]);

        // Get the current admin user from session
        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 400);
        }

        // Check if all administrative approvals are complete
        if (!($renewal->reviewed_by_psg && 
              $renewal->noted_by_dean && 
              $renewal->endorsed_by_osa && 
              $renewal->approved_by_vp)) {
            return response()->json([
                'success' => false,
                'message' => 'All administrative approvals must be complete before final approval.'
            ], 400);
        }

        // Final approval by SAASS
        $renewal->update([
            'status' => 'approved',
            'approved_at' => now(),
            'final_approved_by' => $user->name,
            'final_approved_at' => now(),
        ]);

        // Update the club's last renewal date using the approve method
        $renewal->approve($user->name);

        return response()->json([
            'success' => true,
            'message' => 'Renewal approved successfully! Club renewal cycle has been reset.'
        ]);
    }

    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        // Validate input
        $request->validate([
            'password' => 'required|string',
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Get the current admin user from session
        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 400);
        }

        // Reject the renewal
        $renewal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => $user->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Renewal rejected successfully.'
        ]);
    }

    public function members(Request $request)
    {
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
        $totalMembers = $clubs->sum(function($club) {
            return $club->clubUsers->where('role', 'member')->count();
        });
        $totalOfficers = $clubs->sum(function($club) {
            return $club->clubUsers->where('role', 'officer')->count();
        });
        $totalAdvisers = $clubs->sum(function($club) {
            return $club->clubUsers->where('role', 'adviser')->count();
        });
        $activeClubs = $clubs->where('status', 'active')->count();
        $totalClubs = $clubs->count();

        // Get distinct values for filter dropdowns
        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('dashboard.members', compact(
            'clubs',
            'clubTypes',
            'departments',
            'statuses',
            'totalMembers',
            'totalOfficers',
            'totalAdvisers',
            'activeClubs',
            'totalClubs'
        ));
    }

    public function reports()
    {
        return view('dashboard.reports');
    }

    public function suspendClub(Club $club)
    {
        $previousStatus = $club->status;
        $adminName = session('user')->name ?? 'Unknown Admin';
        $adminRole = session('admin_role') ?? 'Unknown Role';

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
            'admin_role' => $adminRole,
            'timestamp' => now()
        ]);

        return redirect()->route('dashboard.organizations')
            ->with('success', 'Club suspended successfully!');
    }

    public function activateClub(Club $club)
    {
        $previousStatus = $club->status;
        $adminName = session('user')->name ?? 'Unknown Admin';
        $adminRole = session('admin_role') ?? 'Unknown Role';

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
            'admin_role' => $adminRole,
            'timestamp' => now()
        ]);

        return redirect()->route('dashboard.organizations')
            ->with('success', 'Club resumed successfully!');
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        // Get the current admin user from session
        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        // The user session contains the Admin model directly
        $admin = $user;

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found.'
            ], 404);
        }

        // Verify password using Hash::check
        if (Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Password verified successfully.',
                'admin_name' => $admin->name,
                'admin_role' => $adminRole
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password for ' . $admin->name . '. Please try again.',
                'admin_name' => $admin->name
            ], 400);
        }
    }

    public function getCurrentAdminInfo(Request $request)
    {
        // Get the current admin user from session
        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        // The user session contains the Admin model directly
        $admin = $user;

        // Map role to display name
        $roleName = '';
        switch ($adminRole) {
            case 'head_student_affairs':
                $roleName = 'SAASS';
                break;
            case 'director_student_affairs':
                $roleName = 'Director';
                break;
            case 'vp_academics':
                $roleName = 'VP for Academics';
                break;
            case 'dean':
                $roleName = 'Dean';
                break;
            default:
                $roleName = 'Admin';
        }

        return response()->json([
            'success' => true,
            'name' => $admin->name,
            'role' => $roleName,
            'admin_role' => $adminRole
        ]);
    }

    public function profile()
    {
        $admin = session('user');
        $adminRole = session('admin_role');

        // Get role display name
        $roleNames = [
            'head_student_affairs' => 'SAASS',
            'director_student_affairs' => 'Director of Student Affairs',
            'vp_academics' => 'VP for Academics',
            'dean' => 'Dean'
        ];

        $roleName = $roleNames[$adminRole] ?? 'Admin User';

        return view('admin.profile', compact('admin', 'roleName', 'adminRole'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'new_password.regex' => 'The new password must contain at least one uppercase letter and one number.',
            'new_password.min' => 'The new password must be at least 6 characters long.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        $admin = session('user');
        $adminRole = session('admin_role');

        // Verify current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the password directly on the admin model
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        // Update session data
        session(['user' => $admin]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function generateOrganizationReport(Request $request)
    {
        // Validate password first
        $request->validate([
            'admin_password' => 'required',
            'report_type' => 'required|in:all,specific',
            'club_id' => 'required_if:report_type,specific'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Invalid password. Please enter your correct admin password.'], 422);
            }
            return back()->with('error', 'Invalid password. Please enter your correct admin password.');
        }

        $reportType = $request->input('report_type');
        $clubId = $request->input('club_id');

        if ($reportType === 'specific') {
            if (!$clubId) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Please select an organization to generate a report.'], 422);
                }
                return back()->with('error', 'Please select an organization to generate a report.');
            }
            return $this->generateSingleClubReport($clubId);
        }

        // Generate report for all organizations
        $clubs = Club::with(['officers', 'members', 'advisers'])->orderBy('name')->get();

        $data = [
            'clubs' => $clubs,
            'totalClubs' => $clubs->count(),
            'activeClubs' => $clubs->where('status', 'active')->count(),
            'suspendedClubs' => $clubs->where('status', 'suspended')->count(),
            'generatedDate' => now()->format('F j, Y'),
            'generatedTime' => now()->format('g:i A'),
            'adminName' => session('user')->name ?? 'Administrator',
            'adminRole' => $this->getAdminRoleTitle(session('admin_role'))
        ];

        $pdf = Pdf::loadView('reports.organizations-report', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'organizations-report-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function generateMembersReport(Request $request)
    {
        // Validate password first
        $request->validate([
            'admin_password' => 'required',
            'report_type' => 'required|in:all,specific',
            'club_id' => 'required_if:report_type,specific'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Invalid password. Please enter your correct admin password.'], 422);
            }
            return back()->with('error', 'Invalid password. Please enter your correct admin password.');
        }

        $reportType = $request->input('report_type');
        $clubId = $request->input('club_id');

        if ($reportType === 'specific') {
            if (!$clubId) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Please select an organization to generate a report.'], 422);
                }
                return back()->with('error', 'Please select an organization to generate a report.');
            }
            
            $clubs = Club::with(['officers', 'members', 'advisers'])
                ->where('id', $clubId)
                ->get();
        } else {
            $clubs = Club::with(['officers', 'members', 'advisers'])->orderBy('name')->get();
        }

        $totalMembers = $clubs->sum('member_count');
        $totalOfficers = $clubs->sum(function($club) {
            return $club->officers->count();
        });
        $totalAdvisers = $clubs->sum(function($club) {
            return $club->advisers->count();
        });

        $data = [
            'clubs' => $clubs,
            'totalMembers' => $totalMembers,
            'totalOfficers' => $totalOfficers,
            'totalAdvisers' => $totalAdvisers,
            'generatedDate' => now()->format('F j, Y'),
            'generatedTime' => now()->format('g:i A'),
            'adminName' => session('user')->name ?? 'Administrator',
            'adminRole' => $this->getAdminRoleTitle(session('admin_role'))
        ];

        $pdf = Pdf::loadView('reports.members-report', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'members-report-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function generateActivityReport(Request $request)
    {
        // Validate password first
        $request->validate([
            'admin_password' => 'required',
            'time_period' => 'required|in:last_day,last_week,last_month'
        ]);

        // Verify admin password
        $admin = session('user');
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Invalid password. Please enter your correct admin password.'], 422);
            }
            return back()->with('error', 'Invalid password. Please enter your correct admin password.');
        }

        $timePeriod = $request->input('time_period', 'last_month');
        
        // Calculate date range based on time period
        $startDate = match($timePeriod) {
            'last_day' => now()->subDay(),
            'last_week' => now()->subWeek(),
            'last_month' => now()->subMonth(),
            default => now()->subMonth()
        };

        // Get club registrations within the time period
        $registrations = ClubRegistrationRequest::with('officer')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get club renewals within the time period
        $renewals = ClubRenewal::with('club')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get new member registrations within the time period
        $newMembers = ClubUser::where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get club status changes within the time period
        $statusChanges = Club::where('updated_at', '>=', $startDate)
            ->whereNotNull('status')
            ->orderBy('updated_at', 'desc')
            ->get();

        $data = [
            'timePeriod' => $timePeriod,
            'timePeriodLabel' => match($timePeriod) {
                'last_day' => 'Last 24 Hours',
                'last_week' => 'Last Week',
                'last_month' => 'Last Month',
                default => 'Last Month'
            },
            'startDate' => $startDate,
            'endDate' => now(),
            'registrations' => $registrations,
            'renewals' => $renewals,
            'newMembers' => $newMembers,
            'statusChanges' => $statusChanges,
            'summary' => [
                'total_registrations' => $registrations->count(),
                'total_renewals' => $renewals->count(),
                'total_new_members' => $newMembers->count(),
                'total_status_changes' => $statusChanges->count(),
            ],
            'generatedDate' => now()->format('F j, Y'),
            'generatedTime' => now()->format('g:i A'),
            'adminName' => session('user')->name ?? 'Administrator',
            'adminRole' => $this->getAdminRoleTitle(session('admin_role'))
        ];

        $pdf = Pdf::loadView('reports.activity-report', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'activity-report-' . $timePeriod . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function generateSingleClubReport($clubId)
    {
        $club = Club::with(['officers', 'members', 'advisers'])->findOrFail($clubId);

        $data = [
            'club' => $club,
            'generatedDate' => now()->format('F j, Y'),
            'generatedTime' => now()->format('g:i A'),
            'adminName' => session('user')->name ?? 'Administrator',
            'adminRole' => $this->getAdminRoleTitle(session('admin_role'))
        ];

        $pdf = Pdf::loadView('reports.single-club-report', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'club-report-' . str_replace(' ', '-', strtolower($club->name)) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function getAdminRoleTitle($role)
    {
        return match($role) {
            'head_student_affairs' => 'SAASS',
            'director_student_affairs' => 'Director of Student Affairs',
            'vp_academics' => 'Vice President for Academics',
            'dean' => 'Dean',
            default => 'Administrator'
        };
    }
}









