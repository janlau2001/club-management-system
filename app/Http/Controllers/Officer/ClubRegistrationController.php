<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ClubRegistrationRequest;
use Illuminate\Http\Request;

class ClubRegistrationController extends Controller
{
    public function index()
    {
        $officer = session('user');
        $registrations = ClubRegistrationRequest::where('officer_id', $officer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('officer.club-registration', compact('registrations'));
    }

    public function create()
    {
        return view('officer.club-registration-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'club_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'nature' => 'required|in:Academic,Interest',
            'rationale' => 'required|string',
            'recommended_adviser' => 'required|string|max:255',
            'submission_date' => 'required|date',
            'constitution_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'officers_list_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'activities_plan_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'budget_proposal_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $officer = session('user');

        // Handle file uploads
        $files = [];
        $fileFields = ['constitution_file', 'officers_list_file', 'activities_plan_file', 'budget_proposal_file'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $files[$field] = $request->file($field)->store('club-applications', 'public');
            }
        }

        ClubRegistrationRequest::create([
            'officer_id' => $officer->id,
            'club_name' => $request->club_name,
            'department' => $request->department,
            'nature' => $request->nature,
            'rationale' => $request->rationale,
            'recommended_adviser' => $request->recommended_adviser,
            'constitution_file' => $files['constitution_file'] ?? null,
            'officers_list_file' => $files['officers_list_file'] ?? null,
            'activities_plan_file' => $files['activities_plan_file'] ?? null,
            'budget_proposal_file' => $files['budget_proposal_file'] ?? null,
            'submitted_at' => $request->submission_date,
        ]);

        return redirect()->route('officer.club-registration.index')
            ->with('success', 'Application for recognition submitted successfully! Please wait for admin approval.');
    }
}



