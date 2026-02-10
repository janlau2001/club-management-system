<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegistrationRequest;
use App\Models\ClubRenewal;
use App\Models\ClubUser;
use App\Models\Notification;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // ===== Activities & Registrations Methods =====

    public function activities()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $yesterday = now()->subHours(24);
        $allActivities = collect();

        $approvedRegistrations = ClubRegistrationRequest::with('officer')
            ->where('verified_by_osa', true)
            ->where('updated_at', '>', $yesterday)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($approvedRegistrations as $registration) {
            $allActivities->push([
                'type' => 'approved',
                'title' => $registration->club_name . ' approved',
                'description' => 'Registration approved by Head Office • ' . $registration->updated_at->diffForHumans(),
                'time' => $registration->updated_at,
                'color' => 'green',
                'details' => [
                    'club_name' => $registration->club_name,
                    'officer' => $registration->officer->name,
                    'department' => $registration->department
                ]
            ]);
        }

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

        $allActivities = $allActivities->sortByDesc('time');

        return view('head-office.activities', compact('allActivities'));
    }

    public function registrations()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $registrations = ClubRegistrationRequest::with('officer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('head-office.registrations', compact('registrations'));
    }

    public function showRegistration(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $registration->load('officer');
        return view('head-office.registration-details', compact('registration'));
    }

    public function approveRegistration(ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        if (!$registration->verified_by_osa || !$registration->noted_by_director || !$registration->approved_by_vp || !$registration->endorsed_by_dean) {
            return redirect()->route('head-office.registrations.show', $registration)
                ->with('error', 'All four approvals (Head Office, Director, VP, Dean) must be completed before final approval.');
        }

        DB::transaction(function () use ($registration) {
            $club = Club::create([
                'name' => $registration->club_name,
                'department' => $registration->department,
                'club_type' => $registration->nature,
                'description' => $registration->rationale,
                'adviser_name' => $registration->recommended_adviser,
                'adviser_email' => '',
                'date_registered' => now(),
                'member_count' => 1,
                'status' => 'active',
            ]);

            $officer = $registration->officer;
            ClubUser::create([
                'club_id' => $club->id,
                'name' => $officer->name,
                'email' => $officer->email,
                'password' => $officer->password,
                'student_id' => $officer->student_id,
                'role' => 'officer',
                'position' => $officer->position,
                'department' => $officer->department,
                'year_level' => $officer->year_level,
                'joined_date' => now(),
            ]);

            $officer->update([
                'club_status' => 'active',
                'current_club' => $club->name,
                'registration_status' => 'approved',
            ]);

            $registration->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('head-office.registrations')
            ->with('success', 'Club registration approved successfully!');
    }

    public function rejectRegistration(Request $request, ClubRegistrationRequest $registration)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        return redirect()->route('head-office.registrations')
            ->with('success', 'Club registration rejected.');
    }

    /**
     * Show renewals overview page
     */
    public function renewals(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // Get clubs and calculate their renewal status based on date_registered
        $clubsQuery = Club::with(['clubUsers'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->department, function($query, $department) {
                $query->where('department', $department);
            })
            ->when($request->renewal_status, function($query, $status) {
                // Filter after getting clubs since logic depends on computed properties
            });

        $clubs = $clubsQuery->orderBy('date_registered', 'asc')->get();

        // Calculate renewal information for each club
        $renewalData = $clubs->map(function($club) {
            $now = now();
            
            $daysUntilDue = $club->days_until_renewal;
            $renewalDueDate = $club->renewal_due_date;
            
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

            $submittedRenewal = ClubRenewal::where('club_id', $club->id)
                ->whereYear('created_at', now()->year)
                ->latest()
                ->first();

            if ($submittedRenewal && $submittedRenewal->status === 'approved' && $club->last_renewal_date) {
                $renewalStatus = 'not_due';
                $statusLabel = 'Not Due Yet';
                $statusBadge = 'bg-gray-100 text-gray-800';
            }

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
                        return $item->club->last_renewal_date && 
                               $item->club->last_renewal_date->diffInMonths(now()) <= 6;
                    default:
                        return $item->renewal_status === $request->renewal_status;
                }
            });
        }

        // Calculate statistics
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

        return view('head-office.renewals', compact(
            'renewalData',
            'upcomingRenewals',
            'dueRenewals', 
            'overdueRenewals',
            'submittedRenewals'
        ));
    }

    // Decision Support System Methods

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

        // Calculate offense count for each club (3-strike system)
        $clubsWithRisk = $clubs->map(function ($club) {
            // Count confirmed violations only
            $offenseCount = $club->violations->where('status', 'confirmed')->count();
            
            // Determine risk level based on offense count
            if ($offenseCount === 0) {
                $riskLevel = 'none';
                $recommendation = 'No action required. Club in good standing.';
            } elseif ($offenseCount === 1) {
                $riskLevel = 'low';
                $recommendation = '1st Offense: Issue official warning letter.';
            } elseif ($offenseCount === 2) {
                $riskLevel = 'high';
                $recommendation = '2nd Offense: Temporary suspension required.';
            } else { // 3 or more offenses
                $riskLevel = 'critical';
                $recommendation = '3rd Offense: Club termination recommended.';
            }

            $club->offense_count = $offenseCount;
            $club->risk_level = $riskLevel;
            $club->recommendation = $recommendation;

            return $club;
        });

        // Sort by offense count descending
        $clubsWithRisk = $clubsWithRisk->sortByDesc('offense_count');

        // Get pending appeals
        $pendingAppeals = \App\Models\ViolationAppeal::with(['club', 'violation'])
            ->where('status', 'pending')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('head-office.decision-support', compact('clubsWithRisk', 'pendingAppeals'));
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
            'admin_password' => 'required'
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

        // Auto-generate reactivation reason
        $reactivationReason = "Organization reactivated by {$adminName} after administrative review.";

        // Log the action for audit trail
        \Log::info("Organization reactivated via Decision Support System", [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'previous_status' => $previousStatus,
            'new_status' => 'active',
            'reactivation_reason' => $reactivationReason,
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

    /**
     * Get appeal details (JSON)
     */
    public function getAppealDetails($appealId)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $appeal = \App\Models\ViolationAppeal::with(['club', 'violation'])->findOrFail($appealId);

        $attachmentName = null;
        if ($appeal->supporting_documents && is_array($appeal->supporting_documents) && count($appeal->supporting_documents) > 0) {
            $attachmentPath = $appeal->supporting_documents[0];
            $attachmentName = basename($attachmentPath);
        }

        // Count remaining confirmed violations for this club
        $confirmedViolations = \App\Models\Violation::where('club_id', $appeal->club_id)
            ->where('status', 'confirmed')
            ->count();

        // Count total violations (for context)
        $totalViolations = \App\Models\Violation::where('club_id', $appeal->club_id)
            ->whereIn('status', ['confirmed', 'appealed'])
            ->count();

        return response()->json([
            'appeal_id' => $appeal->id,
            'club_name' => $appeal->club->name,
            'club_status' => $appeal->club->status,
            'department' => $appeal->club->department,
            'violation_title' => $appeal->violation->title,
            'violation_description' => $appeal->violation->description,
            'violation_date' => $appeal->violation->violation_date->format('M d, Y'),
            'violation_points' => $appeal->violation->points,
            'violation_severity' => ucfirst($appeal->violation->severity),
            'submitted_by' => $appeal->submitted_by,
            'position' => 'Club Officer', // This could be enhanced if stored
            'submitted_at' => $appeal->submitted_at->format('M d, Y g:i A'),
            'appeal_reason' => $appeal->appeal_reason,
            'has_attachment' => $attachmentName !== null,
            'attachment_name' => $attachmentName,
            'confirmed_violations_count' => $confirmedViolations,
            'total_violations_count' => $totalViolations,
        ]);
    }

    /**
     * Download appeal attachment
     */
    public function downloadAppealAttachment($appealId)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $appeal = \App\Models\ViolationAppeal::findOrFail($appealId);

        if (!$appeal->supporting_documents || !is_array($appeal->supporting_documents) || count($appeal->supporting_documents) === 0) {
            return redirect()->back()->with('error', 'No attachment found.');
        }

        $filePath = $appeal->supporting_documents[0];

        if (!\Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return \Storage::disk('public')->download($filePath);
    }

    /**
     * Accept appeal and lift suspension
     */
    public function acceptAppeal($appealId)
    {
        try {
            if (session('admin_role') !== 'head_student_affairs') {
                return response()->json(['error' => 'Access denied.'], 403);
            }

            $appeal = \App\Models\ViolationAppeal::with(['club', 'violation'])->findOrFail($appealId);
            $club = $appeal->club;
            $violation = $appeal->violation;
            $admin = session('user');
            $adminName = $admin->name ?? 'Unknown Admin';

            // Update appeal status
            $appeal->update([
                'status' => 'approved',
                'reviewed_by' => $adminName,
                'reviewed_at' => now(),
            ]);

            // Update violation status to dismissed (resolved)
            $violation->update([
                'status' => 'dismissed',
            ]);

            // Check for remaining confirmed violations that need appeals
            $remainingViolations = \App\Models\Violation::where('club_id', $club->id)
                ->where('status', 'confirmed')
                ->count();

            $message = 'Appeal accepted successfully.';
            $suspensionLifted = false;

            // Only lift club suspension if:
            // 1. Club is currently suspended
            // 2. There are NO remaining confirmed violations
            if ($club->status === 'suspended') {
                if ($remainingViolations === 0) {
                    $club->update([
                        'status' => 'active',
                        'updated_at' => now()
                    ]);

                    $suspensionLifted = true;
                    $message = 'Appeal accepted successfully. All violations have been resolved and club suspension has been lifted.';

                    \Log::info("Club suspension lifted via appeal acceptance", [
                        'club_id' => $club->id,
                        'club_name' => $club->name,
                        'appeal_id' => $appeal->id,
                        'violation_id' => $violation->id,
                        'admin_name' => $adminName,
                        'timestamp' => now()
                    ]);
                } else {
                    $message = "Appeal accepted successfully. However, the club still has {$remainingViolations} confirmed violation(s) that need to be appealed before suspension can be lifted.";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'suspension_lifted' => $suspensionLifted,
                'remaining_violations' => $remainingViolations
            ]);
        } catch (\Exception $e) {
            \Log::error('Error accepting appeal', [
                'appeal_id' => $appealId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'An error occurred while accepting the appeal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject appeal
     */
    public function rejectAppeal(Request $request, $appealId)
    {
        try {
            if (session('admin_role') !== 'head_student_affairs') {
                return response()->json(['error' => 'Access denied.'], 403);
            }

            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $appeal = \App\Models\ViolationAppeal::with(['club', 'violation'])->findOrFail($appealId);
            $admin = session('user');
            $adminName = $admin->name ?? 'Unknown Admin';

            // Update appeal status
            $appeal->update([
                'status' => 'rejected',
                'reviewed_by' => $adminName,
                'reviewed_at' => now(),
                'review_notes' => $request->reason,
            ]);

            // Violation remains confirmed
            $appeal->violation->update([
                'status' => 'confirmed',
            ]);

            \Log::info("Appeal rejected", [
                'club_id' => $appeal->club->id,
                'club_name' => $appeal->club->name,
                'appeal_id' => $appeal->id,
                'violation_id' => $appeal->violation->id,
                'rejection_reason' => $request->reason,
                'admin_name' => $adminName,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appeal rejected successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error rejecting appeal', [
                'appeal_id' => $appealId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'An error occurred while rejecting the appeal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== Renewal Detail Methods =====

    public function showRenewal($renewalId)
    {
        $renewal = ClubRenewal::with('club')->findOrFail($renewalId);
        return view('head-office.renewal-details', compact('renewal'));
    }

    public function approveRenewal(Request $request, ClubRenewal $renewal)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 400);
        }

        if (!($renewal->reviewed_by_psg && 
              $renewal->noted_by_dean && 
              $renewal->endorsed_by_osa && 
              $renewal->approved_by_vp)) {
            return response()->json([
                'success' => false,
                'message' => 'All administrative approvals must be complete before final approval.'
            ], 400);
        }

        $renewal->update([
            'status' => 'approved',
            'approved_at' => now(),
            'final_approved_by' => $user->name,
            'final_approved_at' => now(),
        ]);

        $renewal->approve($user->name);

        return response()->json([
            'success' => true,
            'message' => 'Renewal approved successfully! Club renewal cycle has been reset.'
        ]);
    }

    public function rejectRenewal(Request $request, ClubRenewal $renewal)
    {
        $request->validate([
            'password' => 'required|string',
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole || session('user_type') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin session not found.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 400);
        }

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

    // ===== Members Methods =====

    public function members(Request $request)
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $query = Club::with(['clubUsers']);

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

        $clubTypes = ['Academic', 'Interest'];
        $departments = ['SASTE', 'SNAHS', 'SITE', 'SBAHM', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $statuses = ['active', 'suspended', 'pending_renewal'];

        return view('head-office.members', compact(
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

    // ===== Reports Methods =====

    public function reports()
    {
        if (session('admin_role') !== 'head_student_affairs') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        return view('head-office.reports');
    }

    public function generateOrganizationReport(Request $request)
    {
        $request->validate([
            'admin_password' => 'required',
            'report_type' => 'required|in:all,specific',
            'club_id' => 'required_if:report_type,specific'
        ]);

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
        $request->validate([
            'admin_password' => 'required',
            'report_type' => 'required|in:all,specific',
            'club_id' => 'required_if:report_type,specific'
        ]);

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
        $request->validate([
            'admin_password' => 'required',
            'time_period' => 'required|in:last_day,last_week,last_month'
        ]);

        $admin = session('user');
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Invalid password. Please enter your correct admin password.'], 422);
            }
            return back()->with('error', 'Invalid password. Please enter your correct admin password.');
        }

        $timePeriod = $request->input('time_period', 'last_month');
        
        $startDate = match($timePeriod) {
            'last_day' => now()->subDay(),
            'last_week' => now()->subWeek(),
            'last_month' => now()->subMonth(),
            default => now()->subMonth()
        };

        $registrations = ClubRegistrationRequest::with('officer')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $renewals = ClubRenewal::with('club')
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $newMembers = ClubUser::where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

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

    // ===== Admin Utility Methods =====

    public function getCurrentAdminInfo()
    {
        $user = session('user');
        $adminRole = session('admin_role');

        if (!$user || !$adminRole) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        return response()->json([
            'name' => $user->name,
            'role' => $adminRole,
            'role_title' => $this->getAdminRoleTitle($adminRole),
        ]);
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = session('user');

        if (!$user) {
            return response()->json(['valid' => false, 'message' => 'Not authenticated'], 401);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false, 'message' => 'Incorrect password']);
    }

    private function getAdminRoleTitle($role)
    {
        return match($role) {
            'head_student_affairs' => 'Head of Student Affairs',
            'director_student_affairs' => 'Director of Student Affairs',
            'vp_academics' => 'Vice President for Academics',
            'dean' => 'Dean',
            default => 'Administrator'
        };
    }
}