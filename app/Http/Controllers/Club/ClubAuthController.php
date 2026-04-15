<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubUser;
use App\Models\Officer;
use App\Models\ClubRegistrationRequest;
use App\Rules\UniqueStudentId;
use App\Rules\PhilippinePhoneNumber;
use App\Rules\UniquePhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClubAuthController extends Controller
{
    public function showLoginForm()
    {
        // Get all active clubs grouped by department
        $departments = ['SASTE', 'SBAHM', 'SNAHS', 'SITE', 'BEU', 'SOM', 'GRADUATE SCHOOL'];
        $clubsByDepartment = [];
        
        foreach ($departments as $department) {
            $clubs = Club::where('department', $department)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
            
            if ($clubs->count() > 0) {
                $clubsByDepartment[$department] = $clubs;
            }
        }
        
        return view('club.login', compact('clubsByDepartment'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find the club user by email only (no department filter)
        $clubUser = ClubUser::with('club')
            ->where('email', $request->email)
            ->where('status', 'active')
            ->first();

        if (!$clubUser || !Hash::check($request->password, $clubUser->password)) {
            return back()->withErrors(['email' => 'Email not registered to any club or invalid password.'])
                        ->withInput();
        }

        // Check if club is suspended
        if ($clubUser->club->status === 'suspended' && !$clubUser->hasAccessDuringSuspension()) {
            return back()->withErrors(['email' => 'This club is currently suspended. Only Presidents, Vice Presidents, and Advisers can access the system during suspension.'])
                        ->withInput();
        }

        // Update online status
        $clubUser->updateOnlineStatus();

        // Set session
        session([
            'club_user' => $clubUser,
            'club' => $clubUser->club,
            'user_type' => 'club_user',
            'authenticated' => true,
            'session_token' => Str::random(60),
            'last_activity' => time()
        ]);

        // Redirect based on role
        if ($clubUser->hasManagementAccess()) {
            return redirect()->route('club.officer.dashboard')->with('success', 'Welcome back, ' . $clubUser->name . '!');
        } else {
            return redirect()->route('club.member.dashboard')->with('success', 'Welcome back, ' . $clubUser->name . '!');
        }
    }

    public function logout(Request $request)
    {
        $clubUser = session('club_user');
        
        if ($clubUser) {
            // Set user offline
            ClubUser::where('id', $clubUser->id)->update(['is_online' => false]);
        }

        // Clear session
        session()->flush();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    public function showOfficerRegistration()
    {
        // Step 0: Email registration (if not verified yet)
        $officerId = request('officer_id');
        
        if ($officerId) {
            $officer = Officer::find($officerId);
            if ($officer && $officer->hasVerifiedEmail() && $officer->registration_status === 'email_verified') {
                // Determine if user needs to set password (Google OAuth) or already has one (manual email)
                // If created_at and email_verified_at are very close (within 5 seconds), it's likely Google OAuth
                $needsPassword = $officer->created_at && $officer->email_verified_at && 
                                 abs($officer->created_at->diffInSeconds($officer->email_verified_at)) < 5;
                
                // Show Step 1: Personal info form
                return view('club.officer-personal-info', compact('officer', 'needsPassword'));
            }
        }
        
        // Show Step 0: Email registration
        return view('club.officer-email-registration');
    }

    public function storeEmailRegistration(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['@gmail.com', '@yahoo.com'];
                    $emailLower = strtolower($value);
                    $domainAllowed = collect($allowedDomains)->contains(fn($d) => str_ends_with($emailLower, $d));
                    if (!$domainAllowed) {
                        $fail('Please use a Gmail (@gmail.com) or Yahoo (@yahoo.com) email address.');
                    }
                    // Block only if an officer record exists AND has an active club_users entry.
                    // This prevents orphaned records (from deleted clubs or abandoned signups) from blocking re-registration.
                    $existing = \App\Models\Officer::where('email', $value)
                        ->whereIn('registration_status', ['approved', 'submitted', 'email_verified'])
                        ->first();
                    if ($existing) {
                        $hasActiveClub = \App\Models\ClubUser::where('email', $value)->exists();
                        if ($hasActiveClub) {
                            $fail('An account with this email already exists. Please log in instead.');
                        }
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Clean up any stale/orphaned officer records for this email before creating a fresh one
        \App\Models\Officer::where('email', $request->email)
            ->whereNotIn('registration_status', ['approved'])
            ->delete();

        // Also purge globally any pending_email_verification records older than 3 minutes
        \App\Models\Officer::where('registration_status', 'pending_email_verification')
            ->where('created_at', '<', now()->subMinutes(3))
            ->delete();

        // Create officer with email only, pending verification
        $officer = Officer::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'registration_status' => 'pending_email_verification',
        ]);

        // Send verification email
        $officer->sendEmailVerificationNotification();

        return redirect()->route('club.verification.notice')
            ->with('success', 'Verification email sent! Please check your inbox.')
            ->with('officer_id', $officer->id);
    }

    public function storeOfficerRegistration(Request $request)
    {
        \Log::info('Officer personal info registration', ['officer_id' => $request->officer_id]);

        // Get the verified officer
        $officer = Officer::findOrFail($request->officer_id);
        
        if (!$officer->hasVerifiedEmail()) {
            return redirect()->route('club.register')
                ->with('error', 'Please verify your email first.');
        }

        // Prepare validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'year_level' => 'required|string',
            'course' => 'required|string|max:255',
            'department' => 'required|string',
            'phone' => [
                'required',
                'string',
                new PhilippinePhoneNumber(),
                new UniquePhoneNumber($officer->id, 'officers')
            ],
            'student_id' => [
                'required',
                'string',
                new UniqueStudentId($officer->id, 'officers')
            ],
        ];

        // Only require password if user needs to set one (Google OAuth users)
        $needsPassword = $officer->created_at && $officer->email_verified_at && 
                         abs($officer->created_at->diffInSeconds($officer->email_verified_at)) < 5;
        
        if ($needsPassword) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Build full name with suffix
        $fullName = $request->first_name . ' ' . $request->last_name;
        if ($request->suffix) {
            $fullName .= ' ' . $request->suffix;
        }

        // Prepare update data
        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'name' => $fullName,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'year_level' => $request->year_level,
            'course' => $request->course,
            'department' => $request->department,
            'position' => 'President', // Automatically set as President
            'registration_status' => 'pending_club_registration',
        ];

        // Only update password if user needed to set one
        if ($needsPassword && $request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update officer with personal information
        $officer->update($updateData);

        \Log::info('Officer personal info saved', ['officer_id' => $officer->id]);

        return redirect()->route('club.club-registration.show', $officer->id)
            ->with('success', 'Personal information saved! Please proceed to club registration.');
    }

    public function showClubRegistration(Officer $officer)
    {
        \Log::info('Showing club registration form', ['officer_id' => $officer->id, 'status' => $officer->registration_status]);

        // Check if officer has already submitted club registration
        if ($officer->registration_status !== 'pending_club_registration') {
            \Log::warning('Officer registration status invalid', ['officer_id' => $officer->id, 'status' => $officer->registration_status]);
            return redirect()->route('club.login')
                ->with('error', 'Club registration has already been submitted.');
        }

        return view('club.club-registration', compact('officer'));
    }

    public function storeClubRegistration(Request $request, Officer $officer)
    {
        $validator = Validator::make($request->only(['club_name', 'nature', 'rationale', 'recommended_adviser', 'constitution_file', 'officers_list_file', 'activities_plan_file', 'budget_proposal_file']), [
            'club_name' => 'required|string|max:255',
            'nature' => 'required|in:Academic,Interest',
            'rationale' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count(trim($value));
                    if ($wordCount < 10) {
                        $fail('The rationale must contain at least 10 words.');
                    }
                },
            ],
            'recommended_adviser' => 'required|string|max:255',
            'constitution_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'officers_list_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'activities_plan_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'budget_proposal_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Store uploaded files
        $constitutionPath = $request->file('constitution_file')->store('club-registrations/constitutions', 'public');
        $officersListPath = $request->file('officers_list_file')->store('club-registrations/officers-lists', 'public');
        $activitiesPlanPath = $request->file('activities_plan_file')->store('club-registrations/activities-plans', 'public');
        $budgetProposalPath = $request->file('budget_proposal_file')->store('club-registrations/budget-proposals', 'public');

        // Create club registration request
        $registration = ClubRegistrationRequest::create([
            'officer_id' => $officer->id,
            'club_name' => $request->club_name,
            'department' => $officer->department,
            'nature' => $request->nature,
            'rationale' => $request->rationale,
            'recommended_adviser' => $request->recommended_adviser,
            'constitution_file' => $constitutionPath,
            'officers_list_file' => $officersListPath,
            'activities_plan_file' => $activitiesPlanPath,
            'budget_proposal_file' => $budgetProposalPath,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Update officer status
        $officer->update([
            'registration_status' => 'submitted',
            'current_club' => $request->club_name,
        ]);

        // Redirect to summary page with officer and club name
        return redirect()->route('club.registration.summary', $officer->id)
            ->with([
                'club_name' => $request->club_name,
                'success' => 'Club registration submitted successfully!'
            ]);
    }

    public function showRegistrationSummary(Officer $officer)
    {
        // Check if officer has submitted registration
        if ($officer->registration_status !== 'submitted') {
            return redirect()->route('club.login')
                ->with('error', 'Invalid access to registration summary.');
        }

        $clubName = session('club_name') ?? $officer->current_club;

        return view('club.registration-summary', compact('officer', 'clubName'));
    }

    public function showRegistrationTracker()
    {
        return view('club.registration-tracker');
    }

    public function checkRegistrationStatus(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find officer by email
        $officer = Officer::where('email', $request->email)->first();

        if (!$officer || !Hash::check($request->password, $officer->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // Get registration request
        $registration = $officer->clubRegistrationRequest;

        if (!$registration) {
            return back()->withErrors(['email' => 'No registration found for this officer.'])->withInput();
        }

        return view('club.registration-tracker', compact('officer', 'registration'));
    }

    public function showRegistrationComplete()
    {
        // Check if info is in session
        if (!session()->has('registration_info')) {
            return redirect()->route('club.login')
                ->with('error', 'Registration session expired. Please log in to continue.');
        }

        $info = session('registration_info');
        
        // Clear info from session after displaying
        session()->forget('registration_info');
        
        return view('club.registration-complete', compact('info'));
    }

    public function cancelRegistration(Officer $officer)
    {
        // Check if officer is in pending_club_registration status
        if ($officer->registration_status === 'pending_club_registration') {
            // Delete the officer record since registration was not completed
            $officer->delete();
            
            // Clear any temporary session data
            session()->forget('temp_password_' . $officer->id);
            
            // Clear localStorage data via session flash message
            session()->flash('clear_local_storage', true);
            
            return redirect()->route('club.login')
                ->with('success', 'Registration cancelled successfully. You can start over anytime.');
        }
        
        // If officer is not in the right status, just redirect to login
        return redirect()->route('club.login')
            ->with('info', 'Registration session ended.');
    }

    public function cleanupIncompleteRegistration(Officer $officer)
    {
        // This endpoint can be called by JavaScript when user leaves or closes the page
        // Only cleanup if registration is not completed
        if ($officer->registration_status !== 'completed' && $officer->registration_status !== 'submitted') {
            // Delete associated club registration request if exists
            if ($officer->clubRegistrationRequest) {
                $officer->clubRegistrationRequest->delete();
            }
            
            // Delete the officer
            $officer->delete();
            
            return response()->json(['success' => true, 'message' => 'Incomplete registration cleaned up.']);
        }
        
        return response()->json(['success' => false, 'message' => 'Registration is already completed or submitted.']);
    }

    public function showRegistrationReedit(ClubRegistrationRequest $registration)
    {
        // Check if registration is rejected
        if ($registration->status !== 'rejected') {
            return redirect()->route('club.registration-tracker')
                ->with('error', 'This registration cannot be edited.');
        }

        // Get the officer who owns this registration
        $officer = $registration->officer;

        return view('club.registration-reedit', compact('registration', 'officer'));
    }

    public function updateRegistrationReedit(Request $request, ClubRegistrationRequest $registration)
    {
        // Check if registration is rejected
        if ($registration->status !== 'rejected') {
            return redirect()->route('club.registration-tracker')
                ->with('error', 'This registration cannot be edited.');
        }

        // Validate the form data
        $validator = Validator::make($request->only(['club_name', 'department', 'nature', 'rationale', 'recommended_adviser', 'constitution_file', 'officers_list_file', 'activities_plan_file', 'budget_proposal_file']), [
            'club_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'nature' => 'required|in:Academic,Interest',
            'rationale' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count(trim($value));
                    if ($wordCount < 10) {
                        $fail('The rationale must contain at least 10 words.');
                    }
                },
            ],
            'recommended_adviser' => 'required|string|max:255',
            'constitution_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'officers_list_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'activities_plan_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'budget_proposal_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'club_name' => $request->club_name,
            'department' => $request->department,
            'nature' => $request->nature,
            'rationale' => $request->rationale,
            'recommended_adviser' => $request->recommended_adviser,
            'status' => 'pending',
            'rejection_reason' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'submitted_at' => now(),
            // Reset all approval flags
            'endorsed_by_dean' => false,
            'endorsed_by_dean_at' => null,
            'endorsed_by_dean_user' => null,
            'approved_by_psg_council' => false,
            'approved_by_psg_council_at' => null,
            'approved_by_psg_council_user' => null,
            'noted_by_director' => false,
            'noted_by_director_at' => null,
            'noted_by_director_user' => null,
            'approved_by_vp' => false,
            'approved_by_vp_at' => null,
            'approved_by_vp_user' => null,
        ];

        // Handle file uploads
        if ($request->hasFile('constitution_file')) {
            // Delete old file if exists
            if ($registration->constitution_file && Storage::disk('public')->exists($registration->constitution_file)) {
                Storage::disk('public')->delete($registration->constitution_file);
            }
            $updateData['constitution_file'] = $request->file('constitution_file')->store('club-documents', 'public');
        }

        if ($request->hasFile('officers_list_file')) {
            if ($registration->officers_list_file && Storage::disk('public')->exists($registration->officers_list_file)) {
                Storage::disk('public')->delete($registration->officers_list_file);
            }
            $updateData['officers_list_file'] = $request->file('officers_list_file')->store('club-documents', 'public');
        }

        if ($request->hasFile('activities_plan_file')) {
            if ($registration->activities_plan_file && Storage::disk('public')->exists($registration->activities_plan_file)) {
                Storage::disk('public')->delete($registration->activities_plan_file);
            }
            $updateData['activities_plan_file'] = $request->file('activities_plan_file')->store('club-documents', 'public');
        }

        if ($request->hasFile('budget_proposal_file')) {
            if ($registration->budget_proposal_file && Storage::disk('public')->exists($registration->budget_proposal_file)) {
                Storage::disk('public')->delete($registration->budget_proposal_file);
            }
            $updateData['budget_proposal_file'] = $request->file('budget_proposal_file')->store('club-documents', 'public');
        }

        // Update the registration
        $registration->update($updateData);

        return redirect()->route('club.registration-tracker')
            ->with('success', 'Registration updated and resubmitted successfully! Your application is now under review again.');
    }

    public function getRegisteredClubs()
    {
        $clubs = Club::where('status', 'active')
            ->select('id', 'name', 'department', 'club_type', 'description')
            ->orderBy('department')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'clubs' => $clubs
        ]);
    }

    public function showApplicationForm(Club $club)
    {
        // Check if the club is active
        if ($club->status !== 'active') {
            return redirect()->route('club.login')
                ->with('error', 'This club is not currently accepting applications.');
        }

        return view('club.application-form', compact('club'));
    }

    public function submitApplication(Request $request, Club $club)
    {
        // Determine if applicant is adviser
        $isAdviser = $request->position === 'adviser';

        // Build validation rules based on position
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|in:Male,Female,Other',
            'phone_number' => ['required', 'string', new PhilippinePhoneNumber],
            'position' => 'required|in:member,officer,adviser',
            'email' => 'required|email|max:255|unique:club_applications,email',
            'password' => 'required|string|min:8|confirmed',
        ];

        $messages = [
            'phone_number.required' => 'Phone number is required.',
            'position.required' => 'Please select a position you are applying for.',
            'position.in' => 'Invalid position selected.',
            'email.unique' => 'This email has already been used for another application.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];

        if ($isAdviser) {
            // Adviser-specific validation
            $rules['professor_id'] = 'required|string|max:50';
            $rules['department_office'] = 'required|string|max:255';
            $messages['professor_id.required'] = 'Professor ID is required.';
            $messages['department_office.required'] = 'Department Office is required.';
        } else {
            // Student/Officer-specific validation
            $rules['age'] = 'required|integer|min:1|max:150';
            $rules['student_id'] = [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request, $club) {
                    // Check if combination of student_id, first_name, and last_name already exists
                    $exists = \App\Models\ClubApplication::where('student_id', $value)
                        ->where('first_name', $request->first_name)
                        ->where('last_name', $request->last_name)
                        ->where('club_id', $club->id)
                        ->exists();
                    
                    if ($exists) {
                        $fail('An application with this student ID and name already exists for this club.');
                    }
                }
            ];
            $rules['department'] = 'required|string|max:255';
            $rules['year_level'] = 'required|string|max:50';
            $messages['student_id.required'] = 'Student ID is required.';
            $messages['department.required'] = 'Course/Program is required.';
            $messages['year_level.required'] = 'Year level is required.';
        }

        // Validate the form data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the application with appropriate fields
        $applicationData = [
            'club_id' => $club->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'position' => $request->position,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ];

        // Add position-specific fields
        if ($isAdviser) {
            $applicationData['professor_id'] = $request->professor_id;
            $applicationData['department_office'] = $request->department_office;
        } else {
            $applicationData['age'] = $request->age;
            $applicationData['student_id'] = $request->student_id;
            $applicationData['department'] = $request->department;
            $applicationData['year_level'] = $request->year_level;
        }

        $application = \App\Models\ClubApplication::create($applicationData);

        return redirect()->route('club.login')
            ->with('success', "Your application to join {$club->name} has been submitted successfully! The club officers will review your application.");
    }
}

