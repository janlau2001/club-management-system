<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password', 'user_type']), [
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:admin,officer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $userType = $request->user_type;

        if ($userType === 'admin') {
            // Find admin by email only, regardless of role
            $admin = Admin::where('email', $credentials['email'])->first();

            if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
                return back()->withErrors(['email' => 'Invalid admin credentials.'])
                            ->withInput()
                            ->with('stay_admin', true);
            }

            session([
                'user' => $admin,
                'user_type' => 'admin',
                'admin_role' => $admin->role,
                'authenticated' => true,
                'session_token' => Str::random(60),
                'last_activity' => time()
            ]);

            // Redirect based on role
            switch ($admin->role) {
                case 'head_student_affairs':
                    return redirect()->route('head-office.dashboard')->with('success', 'Head of Student Affairs login successful!');
                case 'director_student_affairs':
                    return redirect()->route('director.dashboard')->with('success', 'Director login successful!');
                case 'vp_academics':
                    return redirect()->route('vp.dashboard')->with('success', 'VP Academics login successful!');
                case 'dean':
                    return redirect()->route('dean.dashboard')->with('success', 'Dean login successful!');
                case 'psg_council_adviser':
                    return redirect()->route('psg-council.dashboard')->with('success', 'PSG Council Adviser login successful!');
                default:
                    return redirect()->route('dashboard.index')->with('success', 'Admin login successful!');
            }

        } else {
            // Officer login logic remains the same
            $officer = Officer::where('email', $credentials['email'])->first();

            if (!$officer || !Hash::check($credentials['password'], $officer->password)) {
                return back()->withErrors(['email' => 'Invalid officer credentials.'])
                            ->withInput()
                            ->with('stay_officer', true);
            }

            session([
                'user' => $officer,
                'user_type' => 'officer',
                'authenticated' => true,
                'session_token' => Str::random(60),
                'last_activity' => time()
            ]);
            // Direct officers to the new club-centric portal
            return redirect()->route('club.login')->with('success', 'Officer login successful. Please continue via the Club portal.');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->only(['name', 'username', 'department', 'club_status', 'current_club', 'year_level', 'email', 'password', 'password_confirmation']), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:officers',
            'department' => 'required|string|max:255',
            'club_status' => 'required|in:registered,renew',
            'current_club' => 'nullable|string|max:255',
            'year_level' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:officers',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'password.regex' => 'Password must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter, and 1 number.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Officer::create([
                'name' => $request->name,
                'username' => $request->username,
                'department' => $request->department,
                'club_status' => $request->club_status,
                'current_club' => $request->current_club,
                'year_level' => $request->year_level,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        session()->forget(['user', 'user_type', 'admin_role', 'authenticated', 'session_token', 'last_activity']);
        session()->flush();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function refreshSession(Request $request)
    {
        if (!session('authenticated')) {
            return response()->json(['status' => 'error', 'message' => 'Not authenticated'], 401);
        }

        // Update session token and last activity
        session([
            'session_token' => Str::random(60),
            'last_activity' => time()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Session refreshed']);
    }

    public function setNavigationFlag(Request $request)
    {
        // Deprecated method - navigation flag system removed for better session stability
        return response()->json(['status' => 'success', 'message' => 'Navigation flag system disabled']);
    }
}







