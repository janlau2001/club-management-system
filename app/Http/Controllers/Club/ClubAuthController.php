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
        // Check if we're in edit mode (coming back from club registration)
        $editMode = request('edit_mode') == '1';
        $officerId = request('officer_id');
        
        return view('club.officer-registration', compact('editMode', 'officerId'));
    }

    public function storeOfficerRegistration(Request $request)
    {
        \Log::info('Officer registration attempt', ['student_id' => $request->student_id]);

        // Check if we're updating an existing officer (edit mode)
        $editMode = $request->edit_mode == '1';
        $officerId = $request->officer_id;

        // Prepare validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'year_level' => 'required|string',
            'course' => 'required|string|max:255',
            'department' => 'required|string',
            'password' => 'required|string|min:8',
        ];

        // Handle phone validation based on edit mode
        if ($editMode && $officerId) {
            $rules['phone'] = [
                'required',
                'string',
                new PhilippinePhoneNumber(),
                new UniquePhoneNumber($officerId, 'officers')
            ];
        } else {
            $rules['phone'] = [
                'required',
                'string',
                new PhilippinePhoneNumber(),
                new UniquePhoneNumber()
            ];
        }

        // Handle student_id validation based on edit mode
        if ($editMode && $officerId) {
            $rules['student_id'] = [
                'required',
                'string',
                new UniqueStudentId($officerId, 'officers')
            ];
        } else {
            $rules['student_id'] = [
                'required',
                'string',
                new UniqueStudentId()
            ];
        }

        $validator = Validator::make($request->only(['first_name', 'last_name', 'suffix', 'email', 'phone', 'student_id', 'year_level', 'course', 'department', 'password', 'password_confirmation']), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($editMode && $officerId) {
            // Update existing officer
            $officer = Officer::findOrFail($officerId);
            
            // Build full name with suffix
            $fullName = $request->first_name . ' ' . $request->last_name;
            if ($request->suffix) {
                $fullName .= ' ' . $request->suffix;
            }

            $officer->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'name' => $fullName,
                'phone' => $request->phone,
                'student_id' => $request->student_id,
                'year_level' => $request->year_level,
                'course' => $request->course,
                'department' => $request->department,
            ]);

            // Store the plain password temporarily for later display
            session(['temp_password_' . $officer->id => $request->password]);

            return redirect()->route('club.club-registration.show', $officer->id)
                ->with('success', 'Officer information updated! Please continue with club registration.');
        }

        // Build full name with suffix
        $fullName = $request->first_name . ' ' . $request->last_name;
        if ($request->suffix) {
            $fullName .= ' ' . $request->suffix;
        }

        // Generate unique email
        $baseEmail = strtolower(str_replace(' ', '', $request->first_name) . '.' . str_replace(' ', '', $request->last_name));
        $email = $baseEmail . '.' . substr($request->student_id, -4) . '@club.system';
        
        // Ensure email uniqueness
        $counter = 1;
        while (Officer::where('email', $email)->exists()) {
            $email = $baseEmail . '.' . substr($request->student_id, -4) . '.' . $counter . '@club.system';
            $counter++;
        }

        // Check if officer already has a pending registration
        $existingOfficer = Officer::where('student_id', $request->student_id)
            ->where('registration_status', 'pending_club_registration')
            ->first();

        if ($existingOfficer) {
            return redirect()->route('club.club-registration.show', $existingOfficer->id)
                ->with('info', 'You already have a pending registration. Please complete the club registration form.');
        }

        // Create officer record
        $officer = Officer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'name' => $fullName,
            'username' => strtolower(str_replace(' ', '', $request->first_name) . '.' . str_replace(' ', '', $request->last_name) . '.' . substr($request->student_id, -4)),
            'email' => $email,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'year_level' => $request->year_level,
            'course' => $request->course,
            'department' => $request->department,
            'position' => 'Officer',
            'password' => Hash::make($request->password),
            'club_status' => 'pending_registration',
            'current_club' => null,
            'registration_status' => 'pending_club_registration',
        ]);

        // Store the plain password temporarily for later display
        session(['temp_password_' . $officer->id => $request->password]);

        \Log::info('Officer created successfully', ['officer_id' => $officer->id, 'email' => $officer->email]);

        return redirect()->route('club.club-registration.show', $officer->id)
            ->with('success', 'Officer registration completed! Please proceed to club registration.');
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
            'rationale' => 'required|string',
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

        // Store credentials in session for the completion page
        session([
            'registration_credentials' => [
                'email' => $officer->email,
                'password' => session('temp_password_' . $officer->id, 'Not Available'),
                'officer_id' => $officer->id,
                'club_name' => $request->club_name
            ]
        ]);

        // Clear temporary password from session
        session()->forget('temp_password_' . $officer->id);

        return redirect()->route('club.registration.complete')
            ->with('success', 'Club registration submitted successfully! Your application is now under review by the administration.');
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
        // Check if credentials are in session
        if (!session()->has('registration_credentials')) {
            return redirect()->route('club.login')
                ->with('error', 'Registration session expired. Please log in to continue.');
        }

        $credentials = session('registration_credentials');
        
        // Clear credentials from session after displaying
        session()->forget('registration_credentials');
        
        return view('club.registration-complete', compact('credentials'));
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
            'rationale' => 'required|string|min:100',
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
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'age' => 'required|integer|min:1|max:150',
            'gender' => 'required|in:Male,Female,Other',
            'phone_number' => ['required', 'string', new PhilippinePhoneNumber],
            'student_id' => [
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
            ],
            'department' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'position' => 'required|in:member,officer,adviser',
            'email' => 'required|email|max:255|unique:club_applications,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone_number.required' => 'Phone number is required.',
            'student_id.required' => 'Student ID is required.',
            'department.required' => 'Course/Program is required.',
            'year_level.required' => 'Year level is required.',
            'position.required' => 'Please select a position you are applying for.',
            'position.in' => 'Invalid position selected.',
            'email.unique' => 'This email has already been used for another application.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the application
        $application = \App\Models\ClubApplication::create([
            'club_id' => $club->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'age' => $request->age,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'student_id' => $request->student_id,
            'department' => $request->department,
            'year_level' => $request->year_level,
            'position' => $request->position,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        return redirect()->route('club.login')
            ->with('success', "Your application to join {$club->name} has been submitted successfully! The club officers will review your application.");
    }
}

