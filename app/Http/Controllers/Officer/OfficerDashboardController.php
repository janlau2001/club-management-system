<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ClubRegistrationRequest;
use App\Models\Club;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerDashboardController extends Controller
{
    public function dashboard()
    {
        $officer = session('user');
        
        // Get officer's registration requests
        $registrations = ClubRegistrationRequest::where('officer_id', $officer->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get officer's current club if they have one
        $currentClub = null;
        if ($officer->current_club) {
            $currentClub = Club::where('name', $officer->current_club)->first();
        }
        
        // Statistics
        $totalRegistrations = $registrations->count();
        $pendingRegistrations = $registrations->where('status', 'pending')->count();
        $approvedRegistrations = $registrations->where('status', 'approved')->count();
        $rejectedRegistrations = $registrations->where('status', 'rejected')->count();
        
        return view('officer.dashboard', compact(
            'officer',
            'registrations',
            'currentClub',
            'totalRegistrations',
            'pendingRegistrations',
            'approvedRegistrations',
            'rejectedRegistrations'
        ));
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        $officer = session('user');
        
        // Verify current password
        if (!Hash::check($request->current_password, $officer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        // Update password in database
        Officer::where('id', $officer->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        // Update session data
        $updatedOfficer = Officer::find($officer->id);
        session(['user' => $updatedOfficer]);
        
        return back()->with('success', 'Password updated successfully!');
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:officers,email,' . session('user')->id,
            'department' => 'required|string|max:255',
            'year_level' => 'required|string|max:255',
        ]);
        
        $officer = session('user');
        
        // Update profile in database
        Officer::where('id', $officer->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'year_level' => $request->year_level,
        ]);
        
        // Update session data
        $updatedOfficer = Officer::find($officer->id);
        session(['user' => $updatedOfficer]);
        
        return back()->with('success', 'Profile updated successfully!');
    }
}
