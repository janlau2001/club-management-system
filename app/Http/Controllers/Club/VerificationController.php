<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice
     */
    public function notice(Request $request)
    {
        $officerId = session('officer_id') ?? $request->query('officer_id');
        
        if ($officerId) {
            $officer = Officer::find($officerId);
            
            // Redirect if already verified
            if ($officer && $officer->hasVerifiedEmail()) {
                return redirect()->route('club.register', ['officer_id' => $officer->id]);
            }
            
            return view('club.verify-email', compact('officer'));
        }
        
        return redirect()->route('club.register')
            ->with('error', 'Invalid verification session.');
    }

    /**
     * Handle email verification
     */
    public function verify(Request $request, $id, $hash)
    {
        $officer = Officer::find($id);
        
        if (!$officer) {
            return redirect()->route('club.register')
                ->with('error', 'Invalid verification link.');
        }

        // Verify the hash matches the email
        if (!hash_equals((string) $hash, sha1($officer->getEmailForVerification()))) {
            return redirect()->route('club.register')
                ->with('error', 'Invalid verification link.');
        }

        // Verify the signature is valid
        if (!$request->hasValidSignature()) {
            return redirect()->route('club.register')
                ->with('error', 'Verification link has expired.');
        }

        if ($officer->hasVerifiedEmail()) {
            // If already verified, show a page that will close itself
            return view('club.verification-success', [
                'officer' => $officer,
                'alreadyVerified' => true
            ]);
        }

        $officer->markEmailAsVerified();
        
        // Update registration status
        $officer->update(['registration_status' => 'email_verified']);

        // Show success page that will auto-close or redirect
        return view('club.verification-success', [
            'officer' => $officer,
            'alreadyVerified' => false
        ]);
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        $officerId = $request->input('officer_id');
        $officer = Officer::find($officerId);
        
        if (!$officer) {
            return back()->with('error', 'Officer not found.');
        }

        if ($officer->hasVerifiedEmail()) {
            return redirect()->route('club.register', ['officer_id' => $officer->id])
                ->with('info', 'Email already verified.');
        }

        $officer->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent! Please check your email.');
    }

    /**
     * Check verification status (AJAX endpoint)
     */
    public function checkStatus($id)
    {
        $officer = Officer::find($id);
        
        if (!$officer) {
            return response()->json(['verified' => false, 'error' => 'Officer not found'], 404);
        }

        return response()->json([
            'verified' => $officer->hasVerifiedEmail(),
            'status' => $officer->registration_status
        ]);
    }
}
