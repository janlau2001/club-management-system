@extends('club.layouts.app')

@section('title', 'Club Renewal')
@section('page-title', 'Club Renewal')
@section('page-description', 'Manage your club renewal application for ' . date('Y'))
@section('profile-route', route('club.officer.profile'))

@section('navigation')
<!-- Officer/Adviser Navigation -->
<a href="{{ route('club.officer.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21v-4a2 2 0 012-2h4a2 2 0 012 2v4"></path>
    </svg>
    Dashboard
</a>

<a href="{{ route('club.officer.manage-members') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
    </svg>
    Manage Members
</a>

<a href="{{ route('club.officer.club-renewal') }}" class="flex items-center px-4 py-3 text-white bg-white/10">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    Club Renewal
    @if($renewal && (($clubUser->role === 'president' && $renewal->status === 'pending') || ($clubUser->role === 'adviser' && $renewal->status === 'prepared')))
    <span class="ml-auto px-2 py-1 text-xs bg-red-500 text-white">Action Required</span>
    @endif
</a>

<a href="{{ route('club.officer.profile') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    Profile
</a>

<!-- Renewal Status Section -->
<div class="mt-8 px-4">
    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Renewal Status</h3>
    <div class="space-y-2">
        @if($renewal)
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-300">Status</span>
            <span class="px-2 py-1 text-xs 
                {{ $renewal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                   ($renewal->status === 'prepared' ? 'bg-blue-100 text-blue-800' : 
                   ($renewal->status === 'certified' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ ucfirst($renewal->status) }}
            </span>
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-300">Year</span>
            <span class="text-white font-medium">{{ $renewal->year }}</span>
        </div>
        @if($renewal->prepared_by_president)
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-300">Prepared</span>
            <span class="text-green-400 font-medium">✓</span>
        </div>
        @endif
        @if($renewal->certified_by_adviser)
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-300">Certified</span>
            <span class="text-green-400 font-medium">✓</span>
        </div>
        @endif
        @else
        <div class="text-sm text-gray-300">No renewal application found</div>
        @endif
    </div>
</div>
@endsection

@section('header-actions')
<div class="flex items-center space-x-3">
    @if($renewal && (($clubUser->role === 'president' && $renewal->status === 'pending') || ($clubUser->role === 'adviser' && $renewal->status === 'prepared')))
    <button @click="showRenewalModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm font-medium transition-colors flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ $clubUser->role === 'president' ? 'Prepare' : 'Certify' }} Renewal
    </button>
    @endif
    <a href="{{ route('club.officer.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 text-sm font-medium transition-colors flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6" x-data="renewalDashboard()">
    <!-- Renewal Action Modal -->
    @if($renewal && (($clubUser->role === 'president' && $renewal->status === 'pending') || ($clubUser->role === 'adviser' && $renewal->status === 'prepared')))
    <div x-show="showRenewalModal" x-cloak class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white max-w-md w-full mx-4">
            <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">
                    {{ $clubUser->role === 'president' ? 'Prepare' : 'Certify' }} Club Renewal
                </h3>
                <button @click="showRenewalModal = false" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="submitRenewalAction" class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your password to confirm:
                        </label>
                        <input 
                            type="password" 
                            x-model="renewalPassword"
                            required
                            class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900"
                            placeholder="Your password"
                        >
                    </div>
                    
                    <div class="bg-blue-50 p-4">
                        <p class="text-sm text-blue-800">
                            @if($clubUser->role === 'president')
                                By clicking "Prepare", you confirm that all club renewal requirements have been met and the application is ready for adviser certification.
                            @else
                                By clicking "Certify", you confirm that the club renewal application has been reviewed and approved for submission.
                            @endif
                        </p>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button 
                            type="button" 
                            @click="showRenewalModal = false"
                            class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="loading"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white transition-colors disabled:opacity-50"
                        >
                            <span x-show="!loading">
                                {{ $clubUser->role === 'president' ? 'Prepare' : 'Certify' }}
                            </span>
                            <span x-show="loading">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Renewal Status Cards -->
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <main class="p-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- Renewal Form -->
                <div class="bg-white border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Club Renewal Application</h2>
                        <p class="text-gray-600 mt-1">Submit your club renewal for the current academic year</p>
                    </div>

                    <form method="POST" action="{{ route('club.officer.renewal.submit') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">Academic Year *</label>
                                <select id="academic_year" name="academic_year" required
                                        class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                    <option value="">Select Academic Year</option>
                                    <option value="2024-2025" {{ old('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                    <option value="2025-2026" {{ old('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                    <option value="2026-2027" {{ old('academic_year') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                                </select>
                            </div>

                            <div>
                                <label for="last_renewal_date" class="block text-sm font-medium text-gray-700 mb-2">Last Date of Renewal</label>
                                <input type="date" id="last_renewal_date" name="last_renewal_date" 
                                       value="{{ old('last_renewal_date', $lastRenewalDate ?? '') }}" readonly
                                       class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-600">
                                <p class="text-xs text-gray-500 mt-1">Automatically generated based on previous renewal</p>
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" id="department" name="department" 
                                       value="{{ $club->department }}" readonly
                                       class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-600">
                            </div>

                            <div>
                                <label for="nature" class="block text-sm font-medium text-gray-700 mb-2">Nature of Organization *</label>
                                <select id="nature" name="nature" required
                                        class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                    <option value="">Select Nature</option>
                                    <option value="Academic" {{ old('nature', $club->club_type) == 'Academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="Interest" {{ old('nature', $club->club_type) == 'Interest' ? 'selected' : '' }}>Interest</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="faculty_adviser" class="block text-sm font-medium text-gray-700 mb-2">Name of Faculty Adviser *</label>
                            <input type="text" id="faculty_adviser" name="faculty_adviser" 
                                   value="{{ old('faculty_adviser', $club->adviser_name ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                        </div>

                        <div>
                            <label for="rationale" class="block text-sm font-medium text-gray-700 mb-2">Brief Rationale of the Club *</label>
                            <textarea id="rationale" name="rationale" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors"
                                      placeholder="Explain the purpose and continued relevance of your club...">{{ old('rationale', $club->description ?? '') }}</textarea>
                        </div>

                        <!-- Required Documents -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Required Documents</h3>
                            <p class="text-sm text-gray-600">Please upload the following documents (PDF or Word format, max 10MB each):</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="officers_list_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        List of New Officers *
                                        <span class="text-xs text-gray-500">(duly certified by the adviser)</span>
                                    </label>
                                    <input type="file" id="officers_list_file" name="officers_list_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="activities_plan_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Planned Program of Activities *
                                        <span class="text-xs text-gray-500">(signed by the adviser)</span>
                                    </label>
                                    <input type="file" id="activities_plan_file" name="activities_plan_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="budget_proposal_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Budget Proposal *
                                        <span class="text-xs text-gray-500">(signed by treasurer, president, and adviser)</span>
                                    </label>
                                    <input type="file" id="budget_proposal_file" name="budget_proposal_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>

                                <div>
                                    <label for="constitution_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Updated Constitution and By-laws *
                                        <span class="text-xs text-gray-500">(signed by president and adviser)</span>
                                    </label>
                                    <input type="file" id="constitution_file" name="constitution_file" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Club Information Display -->
                        <div class="bg-gray-50 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Club Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Club Name:</span>
                                    <span class="text-gray-900">{{ $club->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Department:</span>
                                    <span class="text-gray-900">{{ $club->department }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Current Status:</span>
                                    <span class="text-gray-900">{{ ucfirst($club->status) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Member Count:</span>
                                    <span class="text-gray-900">{{ $club->member_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Date Registered:</span>
                                    <span class="text-gray-900">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Submitting Officer:</span>
                                    <span class="text-gray-900">{{ $clubUser->name }} ({{ $clubUser->position ?? 'Officer' }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Access Control Notice -->
                        @if(!in_array($clubUser->position, ['President', 'Adviser']) && $clubUser->role !== 'adviser')
                            <div class="bg-yellow-50 border border-yellow-200 p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Access Restricted</h3>
                                        <p class="text-sm text-yellow-700 mt-1">Only the Club President and Faculty Adviser can submit renewal applications.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('club.officer.dashboard') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            @if(in_array($clubUser->position, ['President', 'Adviser']) || $clubUser->role === 'adviser')
                                <button type="submit" 
                                        class="px-6 py-2 bg-orange-600 text-white hover:bg-orange-700 transition-colors font-medium">
                                    Submit Renewal Application
                                </button>
                            @else
                                <button type="button" disabled
                                        class="px-6 py-2 bg-gray-300 text-gray-500 cursor-not-allowed font-medium">
                                    Access Restricted
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
