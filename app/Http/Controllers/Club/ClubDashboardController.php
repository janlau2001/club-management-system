<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use App\Models\ClubUser;
use App\Models\ClubNews;
use App\Models\ClubActivity;
use App\Models\ClubRenewal;
use App\Models\Violation;
use App\Models\ViolationAppeal;
use App\Rules\PhilippinePhoneNumber;
use App\Rules\UniquePhoneNumber;
use App\Rules\UniqueStudentId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClubDashboardController extends Controller
{
    /**
     * Get fresh club data from database
     * This ensures we always have the latest club status (suspended/active)
     */
    private function getFreshClub()
    {
        $sessionClub = session('club');
        if (!$sessionClub) {
            return null;
        }
        
        // Refresh club from database to get latest status
        $freshClub = \App\Models\Club::find($sessionClub->id);
        
        // Update session with fresh data
        if ($freshClub) {
            session(['club' => $freshClub]);
        }
        
        return $freshClub;
    }

    /**
     * Get fresh club user data from database
     * This ensures we always have the latest role/position (e.g. after role changes)
     */
    private function getFreshClubUser()
    {
        $sessionUser = session('club_user');
        if (!$sessionUser) {
            return null;
        }

        $freshUser = ClubUser::with('club')->find($sessionUser->id);

        if ($freshUser) {
            session(['club_user' => $freshUser]);
        }

        return $freshUser;
    }

    public function memberDashboard()
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();
        
        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // If user's role was changed to officer/adviser, redirect them to the officer dashboard
        if ($clubUser->hasManagementAccess()) {
            return redirect()->route('club.officer.dashboard');
        }
        
        // Check if club is suspended and user doesn't have privileged access
        if ($club->status === 'suspended' && !$clubUser->hasAccessDuringSuspension()) {
            return redirect()->route('club.login')->with('error', 'This club is currently suspended. Access is restricted to Presidents, Vice Presidents, and Advisers only.');
        }
        
        // Update user's online status
        $clubUser->updateOnlineStatus();
        
        // Get all club users with online status (including members, officers, and advisers)
        $onlineMembers = $club->clubUsers()->where('role', 'member')
            ->where('is_online', true)
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->get();
            
        $offlineMembers = $club->clubUsers()->where('role', 'member')
            ->where(function($query) {
                $query->where('is_online', false)
                      ->orWhere('last_activity', '<', now()->subMinutes(5))
                      ->orWhereNull('last_activity');
            })->get();
            
        $onlineOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])
            ->where('is_online', true)
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->get();
            
        $offlineOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])
            ->where(function($query) {
                $query->where('is_online', false)
                      ->orWhere('last_activity', '<', now()->subMinutes(5))
                      ->orWhereNull('last_activity');
            })->get();
        
        // Club statistics (properly count all roles)
        $totalMembers = $club->clubUsers()->count(); // Count ALL users: members, officers, and advisers
        $totalOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])->count();
        $onlineMembersCount = $onlineMembers->count();
        $onlineOfficersCount = $onlineOfficers->count();

        // Get club news and activities for member view
        $clubNews = ClubNews::where('club_id', $club->id)
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->get();

        $clubActivities = ClubActivity::where('club_id', $club->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('club.member.dashboard', compact(
            'clubUser',
            'club',
            'onlineMembers',
            'offlineMembers',
            'onlineOfficers',
            'offlineOfficers',
            'totalMembers',
            'totalOfficers',
            'onlineMembersCount',
            'onlineOfficersCount',
            'clubNews',
            'clubActivities'
        ));
    }

    public function memberProfile()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        return view('club.member.profile', compact('clubUser', 'club'));
    }

    public function memberUpdatePassword(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $clubUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        ClubUser::where('id', $clubUser->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function memberUpdateProfile(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year_level' => 'required|string',
            'course' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                new PhilippinePhoneNumber(),
                new UniquePhoneNumber($clubUser->id, 'club_users')
            ],
        ]);

        // Update profile
        ClubUser::where('id', $clubUser->id)->update([
            'name' => $request->name,
            'year_level' => $request->year_level,
            'course' => $request->course,
            'phone' => $request->phone,
        ]);

        // Update session with new data
        $updatedClubUser = ClubUser::find($clubUser->id);
        session(['club_user' => $updatedClubUser]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function memberUpdateEmail(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'new_email' => 'required|email|unique:club_users,email,' . $clubUser->id,
            'current_password' => 'required',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $clubUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update email
        ClubUser::where('id', $clubUser->id)->update([
            'email' => $request->new_email,
        ]);

        // Update session with new data
        $updatedClubUser = ClubUser::find($clubUser->id);
        session(['club_user' => $updatedClubUser]);

        return back()->with('success', 'Email address updated successfully!');
    }

    public function memberViewMembers()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Get all club users (officers and members) for viewing only - force fresh data
        $clubUsers = $club->clubUsers()->orderBy('role', 'desc')->orderBy('name')->get();

        // Add cache control headers to prevent browser caching
        return response()
            ->view('club.member.view-members', compact('clubUser', 'club', 'clubUsers'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function officerDashboard()
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();
        
        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // If user's role was changed to member, redirect them to the member dashboard
        if (!$clubUser->hasManagementAccess()) {
            return redirect()->route('club.member.dashboard');
        }
        
        // Enforce auto-suspension: if club has 2+ confirmed violations, suspend it
        $confirmedViolations = \App\Models\Violation::where('club_id', $club->id)
            ->where('status', 'confirmed')
            ->count();
        if ($confirmedViolations >= 2 && $club->status !== 'suspended') {
            $club->update(['status' => 'suspended']);
            $club = $club->fresh();
        }

        // Check if club is suspended and user doesn't have privileged access
        if ($club->status === 'suspended' && !$clubUser->hasAccessDuringSuspension()) {
            return redirect()->route('club.login')->with('error', 'This club is currently suspended. Access is restricted to Presidents, Vice Presidents, and Advisers only.');
        }
        
        // Update user's online status
        $clubUser->updateOnlineStatus();
        
        // Get all club users with online status (including members, officers, and advisers)
        $onlineMembers = $club->clubUsers()->where('role', 'member')
            ->where('is_online', true)
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->get();
            
        $offlineMembers = $club->clubUsers()->where('role', 'member')
            ->where(function($query) {
                $query->where('is_online', false)
                      ->orWhere('last_activity', '<', now()->subMinutes(5))
                      ->orWhereNull('last_activity');
            })->get();
        
        $onlineOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])
            ->where('is_online', true)
            ->where('last_activity', '>=', now()->subMinutes(5))
            ->get();
            
        $offlineOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])
            ->where(function($query) {
                $query->where('is_online', false)
                      ->orWhere('last_activity', '<', now()->subMinutes(5))
                      ->orWhereNull('last_activity');
            })->get();
        
        // Club statistics (properly count all roles)
        $totalMembers = $club->clubUsers()->count(); // Count ALL users: members, officers, and advisers
        $totalOfficers = $club->clubUsers()->whereIn('role', ['officer', 'adviser'])->count();
        $onlineMembersCount = $onlineMembers->count();
        $onlineOfficersCount = $onlineOfficers->count();
        
        // Count new unviewed pending applications
        $newApplicationsCount = \App\Models\ClubApplication::where('club_id', $club->id)
            ->where('status', 'pending')
            ->whereNull('viewed_at')
            ->count();

        // Get club news and activities
        $clubNews = ClubNews::where('club_id', $club->id)
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->get();

        $clubActivities = ClubActivity::where('club_id', $club->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();
        
        return view('club.officer.dashboard', compact(
            'clubUser',
            'club',
            'onlineMembers',
            'offlineMembers',
            'onlineOfficers',
            'offlineOfficers',
            'totalMembers',
            'totalOfficers',
            'onlineMembersCount',
            'onlineOfficersCount',
            'newApplicationsCount',
            'clubNews',
            'clubActivities'
        ));
    }

    public function officerProfile()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer or Adviser access required.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        return view('club.officer.profile', compact('clubUser', 'club'));
    }

    public function officerUpdatePassword(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer or Adviser access required.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $clubUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        ClubUser::where('id', $clubUser->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function officerUpdateProfile(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer or Adviser access required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year_level' => 'required|string',
            'course' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                new PhilippinePhoneNumber(),
                new UniquePhoneNumber($clubUser->id, 'club_users')
            ],
        ]);

        // Update profile
        ClubUser::where('id', $clubUser->id)->update([
            'name' => $request->name,
            'year_level' => $request->year_level,
            'course' => $request->course,
            'phone' => $request->phone,
        ]);

        // Update session with new data
        $updatedClubUser = ClubUser::find($clubUser->id);
        session(['club_user' => $updatedClubUser]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function officerUpdateEmail(Request $request)
    {
        $clubUser = session('club_user');

        if (!$clubUser || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer or Adviser access required.');
        }

        $request->validate([
            'new_email' => 'required|email|unique:club_users,email,' . $clubUser->id,
            'current_password' => 'required',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $clubUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update email
        ClubUser::where('id', $clubUser->id)->update([
            'email' => $request->new_email,
        ]);

        // Update session with new data
        $updatedClubUser = ClubUser::find($clubUser->id);
        session(['club_user' => $updatedClubUser]);

        return back()->with('success', 'Email address updated successfully!');
    }

    public function manageMembers(Request $request)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasRestrictedManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Access denied. Only Presidents, Vice Presidents, and Advisers can view manage members page.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Get role filter from request
        $roleFilter = $request->get('role', 'all');

        // Get all club users with optional role filter
        $query = $club->clubUsers();
        
        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }
        
        $clubUsers = $query->orderBy('role', 'desc')->orderBy('name')->get();

        // Get counts for each role
        $allCount = $club->clubUsers()->count();
        $memberCount = $club->clubUsers()->where('role', 'member')->count();
        $officerCount = $club->clubUsers()->where('role', 'officer')->count();
        $adviserCount = $club->clubUsers()->where('role', 'adviser')->count();

        // Add cache control headers to prevent browser caching
        return response()
            ->view('club.officer.manage-members', compact('clubUser', 'club', 'clubUsers', 'roleFilter', 'allCount', 'memberCount', 'officerCount', 'adviserCount'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function officerViewMembers()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Officer access required.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Get all club users (officers and members) - force fresh data
        $clubUsers = $club->clubUsers()->orderBy('role', 'desc')->orderBy('name')->get();

        // Add cache control headers to prevent browser caching
        return response()
            ->view('club.officer.view-members', compact('clubUser', 'club', 'clubUsers'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function viewMember(ClubUser $clubUser)
    {
        $currentUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$currentUser || !$club || !$currentUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer or Adviser access required.');
        }

        // Check if the member belongs to the same club
        if ($clubUser->club_id !== $club->id) {
            $redirectRoute = $currentUser->hasRestrictedManagementAccess() ? 'club.officer.manage-members' : 'club.officer.view-members';
            return redirect()->route($redirectRoute)
                ->with('error', 'You can only view members from your club.');
        }

        // Update user's online status
        $currentUser->updateOnlineStatus();

        return view('club.officer.view-member', compact('currentUser', 'club', 'clubUser'));
    }

    public function editMember(ClubUser $clubUser)
    {
        $currentUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$currentUser || !$club || !$currentUser->hasRestrictedManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Access denied. Only Presidents, Vice Presidents, and Advisers can edit members.');
        }

        // Check if the member belongs to the same club
        if ($clubUser->club_id !== $club->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You can only edit members from your club.');
        }

        // Update user's online status
        $currentUser->updateOnlineStatus();

        return view('club.officer.edit-member', compact('currentUser', 'club', 'clubUser'));
    }

    public function updateMember(Request $request, ClubUser $clubUser)
    {
        $currentUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$currentUser || !$club || !$currentUser->hasRestrictedManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Access denied. Only Presidents, Vice Presidents, and Advisers can update members.');
        }

        // Check if the member belongs to the same club
        if ($clubUser->club_id !== $club->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You can only edit members from your club.');
        }

        // Verify current officer's password
        if (!Hash::check($request->current_password, $currentUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Prevent officer/adviser from demoting themselves to member
        if ($clubUser->id === $currentUser->id && $request->role === 'member') {
            return back()->withErrors(['role' => 'You cannot change your own role to member.']);
        }

        // Store original role for logging
        $originalRole = $clubUser->role;
        $originalPosition = $clubUser->position;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:club_users,email,' . $clubUser->id . ',id,club_id,' . $club->id,
            'student_id' => 'required|string|max:255',
            'role' => 'required|in:member,officer,adviser',
            'position' => 'required_if:role,officer|required_if:role,adviser|nullable|string|max:255',
            'year_level' => 'required|string|max:255',
            'current_password' => 'required',
        ], [
            'position.required_if' => 'The position field is required when role is officer or adviser.',
            'role.in' => 'The selected role is invalid. Please choose member, officer, or adviser.',
            'current_password.required' => 'Please enter your current password to verify your identity.',
        ]);

        try {
            // Update the member
            $clubUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'role' => $request->role,
                'position' => ($request->role === 'officer' || $request->role === 'adviser') ? $request->position : null,
                'department' => $club->department, // Use club's department
                'year_level' => $request->year_level,
            ]);

            // Force refresh the model to ensure we have the latest data
            $clubUser = $clubUser->fresh();

            // Update club member count for admin views
            $this->updateClubMemberCount($club);

            // Clear any potential caches and force fresh data
            $this->refreshClubData($club);

            // Log the role change for audit trail
            Log::info('Club member role updated', [
                'updated_by' => $currentUser->name . ' (' . $currentUser->email . ')',
                'updated_user' => $clubUser->name . ' (' . $clubUser->email . ')',
                'original_role' => $originalRole,
                'new_role' => $clubUser->role,
                'original_position' => $originalPosition,
                'new_position' => $clubUser->position,
                'club' => $club->name,
                'timestamp' => now()
            ]);

            // Create a detailed success message
            $successMessage = "Successfully updated {$clubUser->name}! ";
            if ($originalRole !== $clubUser->role) {
                $successMessage .= "Role changed from {$originalRole} to {$clubUser->role}. ";
            }
            if ($clubUser->position) {
                $successMessage .= "Position: {$clubUser->position}. ";
            }
            $successMessage .= "All statistics and member lists have been updated.";

            // Create redirect response with cache-busting headers
            $response = redirect()->route('club.officer.manage-members')
                ->with('success', $successMessage);
            
            // Add cache-busting headers to force browser refresh
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            
            return $response;

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to update club member', [
                'error' => $e->getMessage(),
                'updated_by' => $currentUser->email,
                'target_user' => $clubUser->email,
                'club' => $club->name,
                'timestamp' => now()
            ]);

            return back()->with('error', 'Failed to update member. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function removeMember(Request $request, ClubUser $clubUser)
    {
        $currentUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$currentUser || !$club || !$currentUser->hasRestrictedManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Access denied. Only Presidents, Vice Presidents, and Advisers can remove members.');
        }

        // Check if the member belongs to the same club
        if ($clubUser->club_id !== $club->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You can only remove members from your club.');
        }

        // Prevent removing yourself
        if ($clubUser->id === $currentUser->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You cannot remove yourself from the club.');
        }

        // Verify current officer's password
        $request->validate([
            'current_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, $currentUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Remove from ClubUser table
        $memberName = $clubUser->name;
        $clubUser->delete();

        return redirect()->route('club.officer.manage-members')
            ->with('success', $memberName . ' has been removed from the club.');
    }

    public function changeMemberRole(Request $request, ClubUser $clubUser)
    {
        $currentUser = session('club_user');
        $club = $this->getFreshClub();

        // Only Presidents and Advisers can change roles
        if (!$currentUser || !$club || !($currentUser->position === 'President' || $currentUser->role === 'adviser')) {
            return redirect()->route('club.officer.dashboard')->with('error', 'Access denied. Only Presidents and Advisers can change member roles.');
        }

        // Check if the member belongs to the same club
        if ($clubUser->club_id !== $club->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You can only change roles for members from your club.');
        }

        // Prevent changing your own role
        if ($clubUser->id === $currentUser->id) {
            return redirect()->route('club.officer.manage-members')
                ->with('error', 'You cannot change your own role.');
        }

        // Validate request
        $request->validate([
            'role' => 'required|in:member,officer,adviser',
            'position' => 'required_if:role,officer|required_if:role,adviser|nullable|string|max:255',
            'current_password' => 'required',
        ]);

        // Verify current user's password
        if (!Hash::check($request->current_password, $currentUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // Store original role for logging
        $originalRole = $clubUser->role;
        $originalPosition = $clubUser->position;

        try {
            // Update role and position
            $clubUser->update([
                'role' => $request->role,
                'position' => ($request->role === 'officer' || $request->role === 'adviser') ? $request->position : null,
            ]);

            // Refresh the model
            $clubUser = $clubUser->fresh();

            // Update club member count for admin views
            $this->updateClubMemberCount($club);

            // Clear any potential caches
            $this->refreshClubData($club);

            // Log the role change
            Log::info('Club member role changed', [
                'changed_by' => $currentUser->name . ' (' . $currentUser->email . ') - ' . ($currentUser->position ?: $currentUser->role),
                'changed_user' => $clubUser->name . ' (' . $clubUser->email . ')',
                'original_role' => $originalRole,
                'original_position' => $originalPosition,
                'new_role' => $clubUser->role,
                'new_position' => $clubUser->position,
                'club' => $club->name,
                'timestamp' => now()
            ]);

            // Create success message
            $successMessage = "Successfully changed {$clubUser->name}'s role from {$originalRole} to {$clubUser->role}";
            if ($clubUser->position) {
                $successMessage .= " ({$clubUser->position})";
            }
            $successMessage .= ".";

            return redirect()->route('club.officer.manage-members')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Failed to change member role', [
                'error' => $e->getMessage(),
                'changed_by' => $currentUser->email,
                'target_user' => $clubUser->email,
                'club' => $club->name,
                'timestamp' => now()
            ]);

            return back()->with('error', 'Failed to change member role. Please try again.');
        }
    }

    /**
     * Update the club's member count for admin views
     */
    private function updateClubMemberCount($club)
    {
        $totalMembers = ClubUser::where('club_id', $club->id)->count();

        \App\Models\Club::where('id', $club->id)->update([
            'member_count' => $totalMembers
        ]);
    }

    /**
     * Refresh club data and clear any potential caches
     */
    private function refreshClubData($club)
    {
        // Clear Eloquent model cache
        $club->refresh();
        
        // Force refresh relationships
        $club->load(['clubUsers', 'officers', 'members']);
        
        // Update session data - always refresh to get latest status
        $freshClub = \App\Models\Club::find($club->id);
        if ($freshClub) {
            session(['club' => $freshClub]);
        }
    }

    /**
     * Show club renewal form
     */
    public function showRenewal()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission to access renewal (All Officers and Advisers)
        if ($clubUser->role === 'member') {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Access denied. Only Officers and Advisers can access renewal functions.');
        }

        try {
            // Get last renewal date (placeholder - you can implement this based on your renewal model)
            $lastRenewalDate = null; // This would come from your renewal records
            
            // Add empty renewal variable to prevent undefined variable errors
            $renewal = null;

            return view('club.officer.renewal', compact('clubUser', 'club', 'lastRenewalDate', 'renewal'));
        } catch (\Exception $e) {
            Log::error('Error in showRenewal: ' . $e->getMessage());
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'An error occurred while loading the renewal form.');
        }
    }

    /**
     * Submit club renewal application
     */
    public function submitRenewal(Request $request)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission to submit renewal (All Officers and Advisers)
        if ($clubUser->role === 'member') {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Access denied. Only Officers and Advisers can submit renewal applications.');
        }

        // Validate the renewal form
        $request->validate([
            'academic_year' => 'required|string',
            'nature' => 'required|string',
            'faculty_adviser' => 'required|string|max:255',
            'rationale' => 'required|string|min:50',
            'officers_list_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'activities_plan_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'budget_proposal_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'constitution_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // Create renewal record
            $renewal = new ClubRenewal();
            $renewal->club_id = $club->id;
            $renewal->academic_year = $request->academic_year;
            $renewal->last_renewal_date = $request->last_renewal_date;
            $renewal->department = $club->department;
            $renewal->nature = $request->nature;
            $renewal->faculty_adviser = $request->faculty_adviser;
            $renewal->rationale = $request->rationale;
            $renewal->status = 'pending_internal'; // Start with internal approval process
            $renewal->submitted_at = now();

            // Handle file uploads
            if ($request->hasFile('officers_list_file')) {
                $renewal->officers_list_file = $request->file('officers_list_file')->store('renewals/officers_lists', 'public');
            }
            if ($request->hasFile('activities_plan_file')) {
                $renewal->activities_plan_file = $request->file('activities_plan_file')->store('renewals/activities_plans', 'public');
            }
            if ($request->hasFile('budget_proposal_file')) {
                $renewal->budget_proposal_file = $request->file('budget_proposal_file')->store('renewals/budget_proposals', 'public');
            }
            if ($request->hasFile('constitution_file')) {
                $renewal->constitution_file = $request->file('constitution_file')->store('renewals/constitutions', 'public');
            }

            // Initialize parallel approval fields to false
            $renewal->prepared_by_president = false;
            $renewal->certified_by_adviser = false;
            $renewal->reviewed_by_psg = false;
            $renewal->noted_by_dean = false;
            $renewal->endorsed_by_osa = false;
            $renewal->approved_by_vp = false;

            $renewal->save();

            return redirect()->route('club.officer.renewal')
                ->with('success', 'Renewal application submitted successfully! You can track its progress in the Renewal Status page.');

        } catch (\Exception $e) {
            Log::error('Renewal submission failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit renewal application. Please try again.');
        }
    }

    /**
     * Show renewal status and approvals
     */
    public function renewalStatus()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission to view renewal status
        if ($clubUser->role === 'member') {
            return redirect()->route('club.member.dashboard')
                ->with('error', 'Access denied. Only officers can view renewal status.');
        }

        // All officers and advisers have access to renewal status
        $hasSpecialPermissions = $clubUser->role === 'officer' || $clubUser->role === 'adviser';

        // Get renewal records from database (exclude approved ones)
        $renewals = ClubRenewal::where('club_id', $club->id)
            ->where('status', '!=', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('club.officer.renewal-status', compact('clubUser', 'club', 'renewals', 'hasSpecialPermissions'));
    }

    /**
     * Delete pending renewal
     */
    public function deletePendingRenewal(Request $request, $renewalId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission (All Officers and Advisers)
        $hasSpecialPermissions = $clubUser->role === 'officer' || $clubUser->role === 'adviser';

        if (!$hasSpecialPermissions) {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Access denied. Only Officers and Advisers can delete renewal applications.');
        }

        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!Hash::check($request->password, $clubUser->password)) {
            return redirect()->back()
                ->with('error', 'Invalid password. Please enter your correct password to delete the renewal.');
        }

        try {
            $renewal = ClubRenewal::where('id', $renewalId)
                ->where('club_id', $club->id)
                ->first();

            if (!$renewal) {
                return redirect()->route('club.officer.renewal.status')
                    ->with('error', 'Renewal not found or access denied.');
            }

            // Only allow deletion of pending renewals (not approved ones)
            if ($renewal->status === 'approved') {
                return redirect()->route('club.officer.renewal.status')
                    ->with('error', 'Cannot delete approved renewals.');
            }

            // Delete associated files if they exist
            $files = [
                $renewal->officers_list_file,
                $renewal->activities_plan_file,
                $renewal->budget_proposal_file,
                $renewal->constitution_file
            ];

            foreach ($files as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            $renewal->delete();

            return redirect()->route('club.officer.renewal.status')
                ->with('success', 'Renewal application deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Renewal deletion failed: ' . $e->getMessage());
            return redirect()->route('club.officer.renewal.status')
                ->with('error', 'Failed to delete renewal application. Please try again.');
        }
    }

    /**
     * View renewal details
     */
    public function viewRenewalDetails($renewalId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission to view renewal details
        if ($clubUser->role === 'member') {
            return redirect()->route('club.member.dashboard')
                ->with('error', 'Access denied. Only officers can view renewal details.');
        }

        // All officers and advisers can view renewal details
        $hasSpecialPermissions = $clubUser->role === 'officer' || $clubUser->role === 'adviser';

        try {
            $renewal = ClubRenewal::where('id', $renewalId)
                ->where('club_id', $club->id)
                ->firstOrFail();

            return view('club.officer.renewal-details', compact('clubUser', 'club', 'renewal', 'hasSpecialPermissions'));

        } catch (\Exception $e) {
            return redirect()->route('club.officer.renewal.status')
                ->with('error', 'Renewal not found or access denied.');
        }
    }

    /**
     * Prepare renewal (President action)
     */
    public function prepareRenewal(Request $request, $renewalId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user is an Officer or Adviser
        if ($clubUser->role !== 'officer' && $clubUser->role !== 'adviser') {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Access denied. Only Officers and Advisers can prepare renewal applications.');
        }

        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!Hash::check($request->password, $clubUser->password)) {
            return redirect()->back()
                ->with('error', 'Invalid password. Please enter your correct password to prepare the renewal.');
        }

        try {
            $renewal = ClubRenewal::where('id', $renewalId)
                ->where('club_id', $club->id)
                ->firstOrFail();

            // Debug logging
            Log::info('Prepare renewal attempt', [
                'renewal_id' => $renewalId,
                'renewal_status' => $renewal->status,
                'prepared_by_president' => $renewal->prepared_by_president,
                'can_be_prepared' => $renewal->canBePrepared(),
                'user_position' => $clubUser->position
            ]);

            if (!$renewal->canBePrepared()) {
                return redirect()->route('club.officer.renewal.status')
                    ->with('error', 'This renewal cannot be prepared at this time. Current status: ' . $renewal->status . ', Already prepared: ' . ($renewal->prepared_by_president ? 'Yes' : 'No'));
            }

            $renewal->prepared_by_president = true;
            $renewal->prepared_by_president_at = now();
            $renewal->prepared_by_president_user = $clubUser->name;
            $renewal->status = 'pending_internal';
            $renewal->save();

            return redirect()->route('club.officer.renewal.status')
                ->with('success', 'Renewal application prepared successfully. It is now ready for adviser certification.');

        } catch (\Exception $e) {
            Log::error('Renewal preparation failed: ' . $e->getMessage());
            return redirect()->route('club.officer.renewal.status')
                ->with('error', 'Failed to prepare renewal application. Please try again.');
        }
    }

    /**
     * Certify renewal (Adviser action)
     */
    public function certifyRenewal(Request $request, $renewalId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user is Adviser
        if ($clubUser->position !== 'Adviser' && $clubUser->role !== 'adviser') {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Access denied. Only Advisers can certify renewal applications.');
        }

        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!Hash::check($request->password, $clubUser->password)) {
            return redirect()->back()
                ->with('error', 'Invalid password. Please enter your correct password to certify the renewal.');
        }

        try {
            $renewal = ClubRenewal::where('id', $renewalId)
                ->where('club_id', $club->id)
                ->firstOrFail();

            if (!$renewal->canBeCertified()) {
                return redirect()->route('club.officer.renewal.status')
                    ->with('error', 'This renewal cannot be certified at this time.');
            }

            $renewal->certified_by_adviser = true;
            $renewal->certified_by_adviser_at = now();
            $renewal->certified_by_adviser_user = $clubUser->name;
            $renewal->status = 'pending_admin';
            $renewal->save();

            return redirect()->route('club.officer.renewal.status')
                ->with('success', 'Renewal application certified successfully. It has been submitted to the administration for final approval.');

        } catch (\Exception $e) {
            Log::error('Renewal certification failed: ' . $e->getMessage());
            return redirect()->route('club.officer.renewal.status')
                ->with('error', 'Failed to certify renewal application. Please try again.');
        }
    }

    /**
     * Get club violations for appeal modal
     */
    public function getClubViolations()
    {
        $clubUser = session('club_user');
        
        if (!$clubUser) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $violations = Violation::where('club_id', $clubUser->club_id)
            ->with(['appeals' => function($query) {
                $query->latest();
            }])
            ->orderBy('violation_date', 'desc')
            ->get()
            ->map(function($violation) {
                $latestAppeal = $violation->appeals->first();
                
                return [
                    'id' => $violation->id,
                    'title' => $violation->title,
                    'description' => $violation->description,
                    'severity' => $violation->severity,
                    'severity_color' => $violation->severity_color,
                    'status' => $violation->status,
                    'status_color' => $violation->status_color,
                    'violation_date' => $violation->violation_date->format('M d, Y'),
                    'can_appeal' => $violation->status === 'confirmed' && (!$latestAppeal || $latestAppeal->status === 'rejected'),
                    'appeal_status' => $latestAppeal ? $latestAppeal->status : null,
                    'appeal_status_color' => $latestAppeal ? $latestAppeal->getStatusColor() : null
                ];
            });

        return response()->json([
            'success' => true,
            'violations' => $violations
        ]);
    }

    /**
     * View violations page (dedicated page, not modal)
     */
    public function viewViolationsPage()
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has management access (Officers and Advisers)
        if (!$clubUser->hasManagementAccess()) {
            return redirect()->route('club.member.dashboard')
                ->with('error', 'Only officers and advisers can view and appeal violations.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Get all violations for this club with appeals
        $violations = Violation::where('club_id', $club->id)
            ->with(['appeals' => function($query) {
                $query->latest();
            }])
            ->orderBy('violation_date', 'desc')
            ->get();

        // Get violation statistics
        $confirmedCount = $violations->where('status', 'confirmed')->count();
        $appealedCount = $violations->where('status', 'appealed')->count();
        $resolvedCount = $violations->where('status', 'dismissed')->count();

        return view('club.officer.violations', compact('clubUser', 'club', 'violations', 'confirmedCount', 'appealedCount', 'resolvedCount'));
    }

    /**
     * Show appeal form
     */
    public function showAppealForm($violationId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission
        if (!$clubUser->hasManagementAccess()) {
            return redirect()->route('club.officer.violations')
                ->with('error', 'Only officers and advisers can submit appeals.');
        }

        // Get the violation
        $violation = Violation::where('id', $violationId)
            ->where('club_id', $club->id)
            ->first();

        if (!$violation) {
            return redirect()->route('club.officer.violations')
                ->with('error', 'Violation not found.');
        }

        // Check if violation can be appealed
        $latestAppeal = ViolationAppeal::where('violation_id', $violation->id)->latest()->first();
        if ($violation->status !== 'confirmed' || ($latestAppeal && $latestAppeal->status === 'pending')) {
            return redirect()->route('club.officer.violations')
                ->with('error', 'This violation cannot be appealed at this time.');
        }

        // Get count of confirmed violations (for status indicator)
        $confirmedViolationsCount = Violation::where('club_id', $club->id)
            ->where('status', 'confirmed')
            ->count();

        return view('club.officer.appeal-form', compact('clubUser', 'club', 'violation', 'confirmedViolationsCount'));
    }

    /**
     * Submit violation appeal
     */
    public function submitViolationAppeal(Request $request)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'violation_id' => 'required|exists:violations,id',
            'appeal_reason' => 'required|string|max:2000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max each
        ]);

        $violation = Violation::where('id', $request->violation_id)
            ->where('club_id', $club->id)
            ->first();

        if (!$violation) {
            return redirect()->route('club.officer.violations')
                ->with('error', 'Violation not found.');
        }

        // Check if violation can be appealed
        $latestAppeal = ViolationAppeal::where('violation_id', $violation->id)->latest()->first();
        if ($violation->status !== 'confirmed' || ($latestAppeal && $latestAppeal->status === 'pending')) {
            return redirect()->route('club.officer.violations')
                ->with('error', 'This violation cannot be appealed at this time.');
        }

        // Handle multiple file uploads
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $attachmentPaths[] = $file->storeAs('appeal_attachments', $fileName, 'public');
            }
        }

        // Create appeal with all details
        ViolationAppeal::create([
            'violation_id' => $violation->id,
            'club_id' => $club->id,
            'submitted_by' => $clubUser->name,
            'appeal_reason' => $request->appeal_reason,
            'supporting_documents' => count($attachmentPaths) > 0 ? $attachmentPaths : null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Update violation status to appealed
        $violation->update(['status' => 'appealed']);

        return redirect()->route('club.officer.violations')
            ->with('success', 'Appeal submitted successfully. You will be notified once it is reviewed.');
    }

    public function showApplicants(Request $request)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission (must be officer or adviser)
        if (!in_array($clubUser->role, ['officer', 'adviser'])) {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Only officers and advisers can review applications.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Mark all pending applications as viewed
        \App\Models\ClubApplication::where('club_id', $club->id)
            ->where('status', 'pending')
            ->whereNull('viewed_at')
            ->update(['viewed_at' => now()]);

        // Get status filter from request, default to 'pending'
        $status = $request->get('status', 'pending');

        // Build query based on status
        $query = \App\Models\ClubApplication::where('club_id', $club->id);

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        // Get counts for tabs
        $pendingCount = \App\Models\ClubApplication::where('club_id', $club->id)->where('status', 'pending')->count();
        $approvedCount = \App\Models\ClubApplication::where('club_id', $club->id)->where('status', 'approved')->count();
        $rejectedCount = \App\Models\ClubApplication::where('club_id', $club->id)->where('status', 'rejected')->count();

        return view('club.officer.applicants', compact('clubUser', 'club', 'applications', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function viewApplication($applicationId)
    {
        $clubUser = session('club_user');
        $club = $this->getFreshClub();

        if (!$clubUser || !$club) {
            return redirect()->route('club.login')->with('error', 'Please login first.');
        }

        // Check if user has permission (must be officer or adviser)
        if (!in_array($clubUser->role, ['officer', 'adviser'])) {
            return redirect()->route('club.officer.dashboard')
                ->with('error', 'Only officers and advisers can review applications.');
        }

        // Update user's online status
        $clubUser->updateOnlineStatus();

        // Get the application
        $application = \App\Models\ClubApplication::findOrFail($applicationId);

        // Make sure it belongs to the current club
        if ($application->club_id !== $club->id) {
            return redirect()->route('club.officer.applicants')
                ->with('error', 'Application not found.');
        }

        return view('club.officer.view-applicant', compact('clubUser', 'club', 'application'));
    }

    public function approveApplication(Request $request, $applicationId)
    {
        try {
            $clubUser = session('club_user');
            $club = $this->getFreshClub();

            if (!$clubUser || !$club) {
                return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in again.'], 401);
            }

            // Check if user has permission
            if (!in_array($clubUser->role, ['officer', 'adviser'])) {
                return response()->json(['success' => false, 'message' => 'Only officers and advisers can approve applications.'], 403);
            }

            $application = \App\Models\ClubApplication::findOrFail($applicationId);

            // Make sure it belongs to the current club
            if ($application->club_id !== $club->id) {
                return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
            }

            // Check if already approved
            if ($application->status === 'approved') {
                return response()->json(['success' => false, 'message' => 'This application has already been approved.']);
            }

            // Check if email already exists for this club
            $existingUser = ClubUser::where('club_id', $application->club_id)
                ->where('email', $application->email)
                ->first();
            
            if ($existingUser) {
                return response()->json(['success' => false, 'message' => 'A user with this email already exists in your club.']);
            }

            // Create a club user from the application
            $clubUserData = [
                'club_id' => $application->club_id,
                'name' => trim($application->first_name . ' ' . $application->last_name . ($application->suffix ? ' ' . $application->suffix : '')),
                'email' => $application->email,
                'password' => $application->password, // Already hashed
                'role' => $application->position,
                'position' => $application->position === 'adviser' ? 'Club Adviser' : null,
                'phone' => $application->phone_number,
                'joined_date' => now()->format('Y-m-d'),
                'status' => 'active',
            ];

            // Add position-specific fields
            if ($application->position === 'adviser') {
                $clubUserData['professor_id'] = $application->professor_id;
                $clubUserData['department_office'] = $application->department_office;
            } else {
                $clubUserData['student_id'] = $application->student_id;
                $clubUserData['department'] = $application->department;
                $clubUserData['year_level'] = $application->year_level;
            }

            $newUser = ClubUser::create($clubUserData);

            // Update application status
            $application->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application approved successfully! ' . $application->first_name . ' can now log in as a ' . $application->position . '.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error approving application: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while approving the application. Please try again. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectApplication(Request $request, $applicationId)
    {
        try {
            $clubUser = session('club_user');
            $club = $this->getFreshClub();

            if (!$clubUser || !$club) {
                return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in again.'], 401);
            }

            // Check if user has permission
            if (!in_array($clubUser->role, ['officer', 'adviser'])) {
                return response()->json(['success' => false, 'message' => 'Only officers and advisers can reject applications.'], 403);
            }

            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $application = \App\Models\ClubApplication::findOrFail($applicationId);

            // Make sure it belongs to the current club
            if ($application->club_id !== $club->id) {
                return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
            }

            // Check if already rejected or approved
            if ($application->status === 'rejected') {
                return response()->json(['success' => false, 'message' => 'This application has already been rejected.']);
            }
            
            if ($application->status === 'approved') {
                return response()->json(['success' => false, 'message' => 'Cannot reject an approved application.']);
            }

            // Update application status
            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'rejected_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error rejecting application: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while rejecting the application. Please try again.'
            ], 500);
        }
    }

    // ==========================================
    // Club News CRUD
    // ==========================================

    public function storeNews(Request $request)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer access required.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'required|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('club-news/' . $club->id, 'public');
        }

        ClubNews::create([
            'club_id' => $club->id,
            'author_id' => $clubUser->id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('club.officer.dashboard')->with('success', 'News posted successfully.');
    }

    public function updateNews(Request $request, ClubNews $news)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess() || $news->club_id !== $club->id) {
            return redirect()->route('club.login')->with('error', 'Access denied.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'required|date',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'published_at' => $request->published_at,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('club-news/' . $club->id, 'public');
        }

        if ($request->has('remove_image') && $request->remove_image) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = null;
        }

        $news->update($data);

        return redirect()->route('club.officer.dashboard')->with('success', 'News updated successfully.');
    }

    public function deleteNews(ClubNews $news)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess() || $news->club_id !== $club->id) {
            return redirect()->route('club.login')->with('error', 'Access denied.');
        }

        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('club.officer.dashboard')->with('success', 'News deleted successfully.');
    }

    // ==========================================
    // Club Activities CRUD
    // ==========================================

    public function storeActivity(Request $request)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess()) {
            return redirect()->route('club.login')->with('error', 'Officer access required.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'scheduled_at' => 'required|date',
        ]);

        ClubActivity::create([
            'club_id' => $club->id,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return redirect()->route('club.officer.dashboard')->with('success', 'Activity scheduled successfully.');
    }

    public function updateActivity(Request $request, ClubActivity $activity)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess() || $activity->club_id !== $club->id) {
            return redirect()->route('club.login')->with('error', 'Access denied.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'scheduled_at' => 'required|date',
        ]);

        $activity->update([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return redirect()->route('club.officer.dashboard')->with('success', 'Activity updated successfully.');
    }

    public function deleteActivity(ClubActivity $activity)
    {
        $clubUser = $this->getFreshClubUser();
        $club = $this->getFreshClub();

        if (!$clubUser || !$club || !$clubUser->hasManagementAccess() || $activity->club_id !== $club->id) {
            return redirect()->route('club.login')->with('error', 'Access denied.');
        }

        $activity->delete();

        return redirect()->route('club.officer.dashboard')->with('success', 'Activity deleted successfully.');
    }
}

