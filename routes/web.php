<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\HeadOfficeController;
use App\Http\Controllers\PsgCouncilController;
// Old officer controllers removed - now using club-centric system
use App\Http\Controllers\Club\ClubAuthController;
use App\Http\Controllers\Club\ClubDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VpController;
use Illuminate\Support\Facades\Route;

// Welcome page - show club login directly
Route::get('/', [App\Http\Controllers\Club\ClubAuthController::class, 'showLoginForm']);

// Authentication Routes (with rate limiting for security)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Session refresh route (for AJAX requests)
Route::post('/refresh-session', [AuthController::class, 'refreshSession'])->name('refresh-session')->middleware('check.auth');
Route::post('/set-navigation-flag', [AuthController::class, 'setNavigationFlag'])->name('set-navigation-flag')->middleware('check.auth'); // Kept for compatibility but disabled internally

// Dashboard routes (admin only)
Route::middleware(['check.auth:admin'])->group(function () {
    // Password verification route for sensitive actions
    Route::post('/admin/verify-password', [DashboardController::class, 'verifyPassword'])->name('admin.verify-password');
    // Current admin info route
    Route::get('/admin/current-info', [DashboardController::class, 'getCurrentAdminInfo'])->name('admin.current-info');
    // Admin profile routes
    Route::get('/admin/profile', [DashboardController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/change-password', [DashboardController::class, 'changePassword'])->name('admin.change-password');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Redirect all old dashboard routes to head-office equivalents
        Route::get('/', fn() => redirect()->route('head-office.dashboard'))->name('index');
        Route::get('/activities', fn() => redirect()->route('head-office.activities'))->name('activities');
        Route::get('/organizations', fn() => redirect()->route('head-office.organizations'))->name('organizations');
        Route::get('/organizations/{club}', fn($club) => redirect()->route('head-office.organization.show', $club))->name('organization.show');
        Route::get('/registrations', fn() => redirect()->route('head-office.registrations'))->name('registrations');
        Route::get('/registrations/{registration}', fn($registration) => redirect()->route('head-office.registrations.show', $registration))->name('registrations.show');
        Route::get('/renewals', fn() => redirect()->route('head-office.renewals'))->name('renewals');
        Route::get('/renewals/{renewal}', fn($renewal) => redirect()->route('head-office.renewals.show', $renewal))->name('renewals.show');
        Route::get('/members', fn() => redirect()->route('head-office.members'))->name('members');
        Route::get('/reports', fn() => redirect()->route('head-office.reports'))->name('reports');
    });
});

// Manual Database Cleanup Route (Protected - Admin Only)
Route::middleware(['check.auth:admin'])->get('/manual-cleanup', function() {
    try {
        // Find Computer Science Society
        $csClub = \App\Models\Club::where('name', 'LIKE', '%Computer Science%')->first();
        
        if (!$csClub) {
            return "❌ Computer Science Society not found!";
        }
        
        $csClubId = $csClub->id;
        $output = "🎯 Found Computer Science Society (ID: {$csClubId}): {$csClub->name}<br><br>";
        
        // Show current counts
        $totalClubs = \App\Models\Club::count();
        $totalMembers = \App\Models\ClubUser::count();
        
        $output .= "📊 <strong>Current Database State:</strong><br>";
        $output .= "• Total Clubs: {$totalClubs}<br>";
        $output .= "• Total Members: {$totalMembers}<br>";
        
        // Check if tables exist and show counts
        try {
            $totalRequests = \App\Models\ClubRegistrationRequest::count();
            $output .= "• Total Registration Requests: {$totalRequests}<br>";
        } catch (\Exception $e) {
            $output .= "• Registration Requests table: Not found<br>";
        }
        
        try {
            $totalRenewals = \App\Models\ClubRenewal::count();
            $output .= "• Total Renewals: {$totalRenewals}<br>";
        } catch (\Exception $e) {
            $output .= "• Renewals table: Not found<br>";
        }
        
        $output .= "<br>";
        
        // Delete all club registration requests (clear entire table)
        try {
            $deletedRequests = \App\Models\ClubRegistrationRequest::count();
            \App\Models\ClubRegistrationRequest::truncate();
            $output .= "🗑️ Cleared {$deletedRequests} registration requests<br>";
        } catch (\Exception $e) {
            $output .= "⚠️ Could not clear registration requests: " . $e->getMessage() . "<br>";
        }
        
        // Delete all club renewals (clear entire table)
        try {
            $deletedRenewals = \App\Models\ClubRenewal::count();
            \App\Models\ClubRenewal::truncate();
            $output .= "🗑️ Cleared {$deletedRenewals} club renewals<br>";
        } catch (\Exception $e) {
            $output .= "⚠️ Could not clear renewals: " . $e->getMessage() . "<br>";
        }
        
        // Delete club members from other clubs
        $deletedMembers = \App\Models\ClubUser::where('club_id', '!=', $csClubId)->delete();
        $output .= "🗑️ Deleted {$deletedMembers} members from other clubs<br>";
        
        // Delete other clubs
        $deletedClubs = \App\Models\Club::where('id', '!=', $csClubId)->delete();
        $output .= "🗑️ Deleted {$deletedClubs} other clubs<br><br>";
        
        // Show final counts
        $finalClubs = \App\Models\Club::count();
        $finalMembers = \App\Models\ClubUser::count();
        
        $output .= "✅ <strong>Final Database State:</strong><br>";
        $output .= "• Total Clubs: {$finalClubs}<br>";
        $output .= "• Total Members: {$finalMembers}<br>";
        
        try {
            $finalRequests = \App\Models\ClubRegistrationRequest::count();
            $output .= "• Total Requests: {$finalRequests}<br>";
        } catch (\Exception $e) {
            $output .= "• Requests: Cleared<br>";
        }
        
        try {
            $finalRenewals = \App\Models\ClubRenewal::count();
            $output .= "• Total Renewals: {$finalRenewals}<br>";
        } catch (\Exception $e) {
            $output .= "• Renewals: Cleared<br>";
        }
        
        $output .= "<br>🎉 <strong>Database cleanup completed successfully!</strong><br>";
        $output .= "Only Computer Science Society and admin accounts remain.<br><br>";
        $output .= '<a href="/dashboard" style="background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Dashboard</a>';
        
        return $output;
        
    } catch (\Exception $e) {
        return "❌ Error during cleanup: " . $e->getMessage() . "<br><br>Please check your database structure.";
    }
});

// Old Officer Routes - REMOVED (Now using Club-Centric System)
// All officer functionality is now handled through the club system

// Director Routes
Route::middleware(['check.auth:admin'])->prefix('director')->name('director.')->group(function () {
    Route::get('/dashboard', [DirectorController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations', function() { return redirect()->route('director.dashboard'); }); // Redirect old route
    Route::get('/organizations/{club}', [DirectorController::class, 'showOrganization'])->name('organization.show');
    Route::get('/approvals', [DirectorController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{registration}', [DirectorController::class, 'showApproval'])->name('approvals.show');
    Route::get('/approvals/{registration}/document/{type}', [DirectorController::class, 'downloadDocument'])->name('approvals.document');
    Route::post('/approvals/{registration}/approve', [DirectorController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{registration}/reject', [DirectorController::class, 'reject'])->name('approvals.reject');

    // Renewal Approvals (Director endorsement step)
    Route::get('/renewal-approvals', [DirectorController::class, 'renewalApprovals'])->name('renewal-approvals');
    Route::get('/renewal-approvals/{renewal}', [DirectorController::class, 'showRenewalApproval'])->name('renewal-approvals.show');
    Route::post('/renewal-approvals/{renewal}/endorse', [DirectorController::class, 'endorseRenewal'])->name('renewal-approvals.endorse');
    Route::post('/renewal-approvals/{renewal}/reject', [DirectorController::class, 'rejectRenewal'])->name('renewal-approvals.reject');
});

// VP Academics Routes
Route::middleware(['check.auth:admin'])->prefix('vp')->name('vp.')->group(function () {
    Route::get('/dashboard', [VpController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations', function() { return redirect()->route('vp.dashboard'); }); // Redirect old route
    Route::get('/organizations/{club}', [VpController::class, 'showOrganization'])->name('organization.show');
    Route::get('/approvals', [VpController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{registration}', [VpController::class, 'showApproval'])->name('approvals.show');
    Route::get('/approvals/{registration}/document/{type}', [VpController::class, 'downloadDocument'])->name('approvals.document');
    Route::post('/approvals/{registration}/approve', [VpController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{registration}/reject', [VpController::class, 'reject'])->name('approvals.reject');

    // Renewal Approvals (VP approval step)
    Route::get('/renewal-approvals', [VpController::class, 'renewalApprovals'])->name('renewal-approvals');
    Route::get('/renewal-approvals/{renewal}', [VpController::class, 'showRenewalApproval'])->name('renewal-approvals.show');
    Route::post('/renewal-approvals/{renewal}/approve', [VpController::class, 'approveRenewal'])->name('renewal-approvals.approve');
    Route::post('/renewal-approvals/{renewal}/reject', [VpController::class, 'rejectRenewal'])->name('renewal-approvals.reject');
});

// PSG Council Adviser Routes
Route::middleware(['check.auth:admin'])->prefix('psg-council')->name('psg-council.')->group(function () {
    Route::get('/dashboard', [PsgCouncilController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations/{club}', [PsgCouncilController::class, 'showOrganization'])->name('organization.show');

    // Approvals (Club Registrations)
    Route::get('/approvals', [PsgCouncilController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{registration}', [PsgCouncilController::class, 'showApproval'])->name('approvals.show');
    Route::get('/approvals/{registration}/document/{type}', [PsgCouncilController::class, 'viewDocument'])->name('approvals.document');
    Route::post('/approvals/{registration}/approve', [PsgCouncilController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{registration}/reject', [PsgCouncilController::class, 'reject'])->name('approvals.reject');

    // Renewal Approvals (PSG Council noting)
    Route::get('/renewal-approvals', [PsgCouncilController::class, 'renewalApprovals'])->name('renewal-approvals');
    Route::get('/renewal-approvals/{renewal}', [PsgCouncilController::class, 'showRenewalApproval'])->name('renewal-approvals.show');
    Route::post('/renewal-approvals/{renewal}/note', [PsgCouncilController::class, 'noteRenewal'])->name('renewal-approvals.note');
    Route::post('/renewal-approvals/{renewal}/approve', [PsgCouncilController::class, 'approveRenewal'])->name('renewal-approvals.approve');
    Route::post('/renewal-approvals/{renewal}/reject', [PsgCouncilController::class, 'rejectRenewal'])->name('renewal-approvals.reject');
});

// Dean Routes
Route::middleware(['check.auth:admin'])->prefix('dean')->name('dean.')->group(function () {
    Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations', function() { return redirect()->route('dean.dashboard'); }); // Redirect old route
    Route::get('/organizations/{club}', [DeanController::class, 'showOrganization'])->name('organization.show');
    Route::get('/approvals', [DeanController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{registration}', [DeanController::class, 'showApproval'])->name('approvals.show');
    Route::get('/approvals/{registration}/document/{type}', [DeanController::class, 'viewDocument'])->name('approvals.document');
    Route::post('/approvals/{registration}/approve', [DeanController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{registration}/reject', [DeanController::class, 'reject'])->name('approvals.reject');

    // Renewal Approvals (Dean noting step)
    Route::get('/renewal-approvals', [DeanController::class, 'renewalApprovals'])->name('renewal-approvals');
    Route::get('/renewal-approvals/{renewal}', [DeanController::class, 'showRenewalApproval'])->name('renewal-approvals.show');
    Route::post('/renewal-approvals/{renewal}/note', [DeanController::class, 'noteRenewal'])->name('renewal-approvals.note');
    Route::post('/renewal-approvals/{renewal}/reject', [DeanController::class, 'rejectRenewal'])->name('renewal-approvals.reject');
});

// Head Office Routes
Route::middleware(['check.auth:admin'])->prefix('head-office')->name('head-office.')->group(function () {
    Route::get('/dashboard', [HeadOfficeController::class, 'dashboard'])->name('dashboard');
    Route::get('/activities', [HeadOfficeController::class, 'activities'])->name('activities');
    Route::get('/organizations', [HeadOfficeController::class, 'organizations'])->name('organizations');
    Route::get('/organizations/{club}', [HeadOfficeController::class, 'showOrganization'])->name('organization.show');
    Route::patch('/organizations/{club}/suspend', [HeadOfficeController::class, 'suspendOrganization'])->name('organization.suspend');
    Route::patch('/organizations/{club}/activate', [HeadOfficeController::class, 'activateOrganization'])->name('organization.activate');
    Route::get('/registrations', [HeadOfficeController::class, 'registrations'])->name('registrations');
    Route::get('/registrations/{registration}', [HeadOfficeController::class, 'showRegistration'])->name('registrations.show');
    Route::post('/registrations/{registration}/approve', [HeadOfficeController::class, 'approveRegistration'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [HeadOfficeController::class, 'rejectRegistration'])->name('registrations.reject');
    Route::get('/approvals', [HeadOfficeController::class, 'approvals'])->name('approvals');
    Route::get('/approvals/{registration}', [HeadOfficeController::class, 'showApproval'])->name('approvals.show');
    Route::get('/approvals/{registration}/document/{type}', [HeadOfficeController::class, 'viewDocument'])->name('approvals.document');
    Route::post('/approvals/{registration}/verify', [HeadOfficeController::class, 'verify'])->name('approvals.verify');
    Route::post('/approvals/{registration}/reject', [HeadOfficeController::class, 'reject'])->name('approvals.reject');
    Route::get('/renewals', [HeadOfficeController::class, 'renewals'])->name('renewals');
    Route::get('/renewals/{renewal}', [HeadOfficeController::class, 'showRenewal'])->name('renewals.show');
    Route::post('/renewals/{renewal}/approve', [HeadOfficeController::class, 'approveRenewal'])->name('renewals.approve');
    Route::post('/renewals/{renewal}/reject', [HeadOfficeController::class, 'rejectRenewal'])->name('renewals.reject');
    Route::post('/renewals/send-reminder', [NotificationController::class, 'sendRenewalReminder'])->name('renewals.send-reminder');

    // Members & Reports
    Route::get('/members', [HeadOfficeController::class, 'members'])->name('members');
    Route::get('/reports', [HeadOfficeController::class, 'reports'])->name('reports');
    Route::post('/reports/organizations', [HeadOfficeController::class, 'generateOrganizationReport'])->name('reports.organizations');
    Route::post('/reports/members', [HeadOfficeController::class, 'generateMembersReport'])->name('reports.members');
    Route::post('/reports/activities', [HeadOfficeController::class, 'generateActivityReport'])->name('reports.activities');
    Route::get('/reports/club/{club}', [HeadOfficeController::class, 'generateSingleClubReport'])->name('reports.single-club');

    // Admin utility routes
    Route::get('/admin-info', [HeadOfficeController::class, 'getCurrentAdminInfo'])->name('admin.info');
    Route::post('/verify-password', [HeadOfficeController::class, 'verifyPassword'])->name('verify.password');
    
    // Decision Support System routes
    Route::get('/decision-support', [HeadOfficeController::class, 'decisionSupport'])->name('decision-support');
    Route::get('/decision-support/appeals', [HeadOfficeController::class, 'appeals'])->name('decision-support.appeals');
    Route::get('/decision-support/club/{club}', [HeadOfficeController::class, 'clubViolationDetails'])->name('decision-support.club-details');
    Route::post('/decision-support/record-violation', [HeadOfficeController::class, 'recordViolation'])->name('decision-support.record-violation');
    Route::post('/decision-support/suspend/{club}', [HeadOfficeController::class, 'suspendClubWithAuthentication'])->name('decision-support.suspend');
    Route::post('/decision-support/reactivate/{club}', [HeadOfficeController::class, 'reactivateClubWithAuthentication'])->name('decision-support.reactivate');
    
    // Appeal Management Routes
    Route::get('/decision-support/appeal/{appeal}', [HeadOfficeController::class, 'getAppealDetails'])->name('decision-support.appeal-details');
    Route::get('/decision-support/appeal/{appeal}/download/{fileIndex?}', [HeadOfficeController::class, 'downloadAppealAttachment'])->name('decision-support.appeal-download');
    Route::post('/decision-support/appeal/{appeal}/accept', [HeadOfficeController::class, 'acceptAppeal'])->name('decision-support.appeal-accept');
    Route::post('/decision-support/appeal/{appeal}/reject', [HeadOfficeController::class, 'rejectAppeal'])->name('decision-support.appeal-reject');
});

// Club Routes (New Club-Centric System)
Route::prefix('club')->name('club.')->group(function () {
    // Club Authentication (with rate limiting for security)
    Route::get('/login', [ClubAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ClubAuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::post('/logout', [ClubAuthController::class, 'logout'])->name('logout');

    // Email Verification Routes
    Route::get('/email/verify', [App\Http\Controllers\Club\VerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Club\VerificationController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [App\Http\Controllers\Club\VerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.resend');
    Route::get('/verification/check-status/{officer}', [App\Http\Controllers\Club\VerificationController::class, 'checkStatus'])
        ->name('verification.check-status');

    // Club Registration Routes (Multi-step)
    Route::get('/register', [ClubAuthController::class, 'showOfficerRegistration'])->name('register');
    Route::post('/email-registration', [ClubAuthController::class, 'storeEmailRegistration'])->name('email-registration.store');
    
    // Google OAuth Routes
    Route::get('/auth/google', [ClubAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [ClubAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    
    Route::post('/officer-registration', [ClubAuthController::class, 'storeOfficerRegistration'])->name('officer-registration.store');
    Route::get('/club-registration/{officer}', [ClubAuthController::class, 'showClubRegistration'])->name('club-registration.show');
    Route::post('/club-registration/{officer}', [ClubAuthController::class, 'storeClubRegistration'])->name('club-registration.store');
    Route::post('/club-registration/{officer}/cancel', [ClubAuthController::class, 'cancelRegistration'])->name('club-registration.cancel');
    Route::delete('/registration/cleanup/{officer}', [ClubAuthController::class, 'cleanupIncompleteRegistration'])->name('registration.cleanup');
    Route::get('/registration-summary/{officer}', [ClubAuthController::class, 'showRegistrationSummary'])->name('registration.summary');
    Route::get('/registration-complete', [ClubAuthController::class, 'showRegistrationComplete'])->name('registration.complete');

    // Registration Tracker Routes
    Route::get('/registration-tracker', [ClubAuthController::class, 'showRegistrationTracker'])->name('registration-tracker');
    Route::post('/registration-tracker', [ClubAuthController::class, 'checkRegistrationStatus'])->name('registration-tracker.check');
    
    // Registered Clubs List (for Join Club feature)
    Route::get('/registered-clubs', [ClubAuthController::class, 'getRegisteredClubs'])->name('registered-clubs');
    
    // Club Application Routes
    Route::get('/club-application/{club}', [ClubAuthController::class, 'showApplicationForm'])->name('club-application.form');
    Route::post('/club-application/{club}', [ClubAuthController::class, 'submitApplication'])->name('club-application.submit');
    
    // Re-edit Registration Routes
    Route::get('/registration-reedit/{registration}', [ClubAuthController::class, 'showRegistrationReedit'])->name('registration-reedit');
    Route::put('/registration-reedit/{registration}', [ClubAuthController::class, 'updateRegistrationReedit'])->name('registration-reedit.update');

    // Club Member Dashboard
    Route::get('/member/dashboard', [ClubDashboardController::class, 'memberDashboard'])->name('member.dashboard');
    Route::get('/member/profile', [ClubDashboardController::class, 'memberProfile'])->name('member.profile');
    Route::put('/member/update-profile', [ClubDashboardController::class, 'memberUpdateProfile'])->name('member.update-profile');
    Route::put('/member/update-email', [ClubDashboardController::class, 'memberUpdateEmail'])->name('member.update-email');
    Route::post('/member/update-password', [ClubDashboardController::class, 'memberUpdatePassword'])->name('member.update-password');
    Route::get('/member/view-members', [ClubDashboardController::class, 'memberViewMembers'])->name('member.view-members');

    // Club Officer Dashboard and Management
    Route::get('/officer/dashboard', [ClubDashboardController::class, 'officerDashboard'])->name('officer.dashboard');
    Route::get('/officer/profile', [ClubDashboardController::class, 'officerProfile'])->name('officer.profile');
    Route::put('/officer/update-profile', [ClubDashboardController::class, 'officerUpdateProfile'])->name('officer.update-profile');
    Route::put('/officer/update-email', [ClubDashboardController::class, 'officerUpdateEmail'])->name('officer.update-email');
    Route::post('/officer/update-password', [ClubDashboardController::class, 'officerUpdatePassword'])->name('officer.update-password');
    Route::get('/officer/manage-members', [ClubDashboardController::class, 'manageMembers'])->name('officer.manage-members');
    Route::get('/officer/view-members', [ClubDashboardController::class, 'officerViewMembers'])->name('officer.view-members');
    Route::get('/officer/member/{clubUser}', [ClubDashboardController::class, 'viewMember'])->name('officer.member.view');
    Route::get('/officer/member/{clubUser}/edit', [ClubDashboardController::class, 'editMember'])->name('officer.member.edit');
    Route::post('/officer/member/{clubUser}/update', [ClubDashboardController::class, 'updateMember'])->name('officer.member.update');
    Route::put('/officer/member/{clubUser}/change-role', [ClubDashboardController::class, 'changeMemberRole'])->name('officer.member.change-role');
    Route::post('/officer/member/{clubUser}/remove', [ClubDashboardController::class, 'removeMember'])->name('officer.member.remove');

    // Club Renewal Routes (for Presidents and Advisers)
    Route::get('/officer/renewal', [ClubDashboardController::class, 'showRenewal'])->name('officer.renewal');
    Route::post('/officer/renewal', [ClubDashboardController::class, 'submitRenewal'])->name('officer.renewal.submit');
    Route::get('/officer/renewal/status', [ClubDashboardController::class, 'renewalStatus'])->name('officer.renewal.status');
    Route::get('/officer/renewal/{renewal}/details', [ClubDashboardController::class, 'viewRenewalDetails'])->name('officer.renewal.details');
    Route::delete('/officer/renewal/{renewal}/delete', [ClubDashboardController::class, 'deletePendingRenewal'])->name('officer.renewal.delete');
    Route::post('/officer/renewal/{renewal}/prepare', [ClubDashboardController::class, 'prepareRenewal'])->name('officer.renewal.prepare');
    Route::post('/officer/renewal/{renewal}/certify', [ClubDashboardController::class, 'certifyRenewal'])->name('officer.renewal.certify');

    // Club Applicants Routes (for Officers and Advisers)
    Route::get('/officer/applicants', [ClubDashboardController::class, 'showApplicants'])->name('officer.applicants');
    Route::get('/officer/applicants/{application}', [ClubDashboardController::class, 'viewApplication'])->name('officer.applicants.view');
    Route::post('/officer/applicants/{application}/approve', [ClubDashboardController::class, 'approveApplication'])->name('officer.applicants.approve');
    Route::post('/officer/applicants/{application}/reject', [ClubDashboardController::class, 'rejectApplication'])->name('officer.applicants.reject');

    // Violation Appeals Routes
    Route::get('/officer/violations', [ClubDashboardController::class, 'viewViolationsPage'])->name('officer.violations');
    Route::get('/officer/violations/data', [ClubDashboardController::class, 'getClubViolations'])->name('officer.violations.data');
    Route::get('/officer/appeal-form/{violation}', [ClubDashboardController::class, 'showAppealForm'])->name('officer.appeal-form');
    Route::post('/officer/submit-appeal', [ClubDashboardController::class, 'submitViolationAppeal'])->name('officer.submit-appeal');

    // Club Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Club News Routes (Officers/Advisers only)
    Route::post('/officer/news', [ClubDashboardController::class, 'storeNews'])->name('officer.news.store');
    Route::put('/officer/news/{news}', [ClubDashboardController::class, 'updateNews'])->name('officer.news.update');
    Route::delete('/officer/news/{news}', [ClubDashboardController::class, 'deleteNews'])->name('officer.news.delete');

    // Club Activities Routes (Officers/Advisers only)
    Route::post('/officer/activities', [ClubDashboardController::class, 'storeActivity'])->name('officer.activities.store');
    Route::put('/officer/activities/{activity}', [ClubDashboardController::class, 'updateActivity'])->name('officer.activities.update');
    Route::delete('/officer/activities/{activity}', [ClubDashboardController::class, 'deleteActivity'])->name('officer.activities.delete');
    
    // Debug route removed for production security
    // If needed for development, protect with authentication middleware

    // Status refresh for online tracking
    Route::post('/refresh-status', function() {
        $clubUser = session('club_user');
        if ($clubUser) {
            \App\Models\ClubUser::where('id', $clubUser->id)->update([
                'is_online' => true,
                'last_activity' => now(),
            ]);
        }
        return response()->json(['status' => 'ok']);
    });
});


















