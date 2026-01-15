<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Renewal Status - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Renewal Status & Approvals</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                    <p class="text-white opacity-75 text-sm">{{ $clubUser->name }} ({{ $clubUser->position ?? 'Officer' }})</p>
                </div>
                <a href="{{ route('club.officer.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </header>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Authority Information -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Your Authority Level</h2>
                                <p class="text-gray-600 mt-1">What you can do with renewal applications</p>
                            </div>
                    
                    <div class="p-6">
                        @if($clubUser->position === 'President' || $clubUser->position === 'Vice President')
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-yellow-800">{{ $clubUser->position }} Authority</h3>
                                        <p class="text-yellow-700 mt-1">You can prepare renewal applications and submit them for adviser certification.</p>
                                        <ul class="text-sm text-yellow-600 mt-2 list-disc list-inside">
                                            <li>Review and prepare renewal documents</li>
                                            <li>Submit applications to adviser for certification</li>
                                            <li>Track renewal status and progress</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @elseif($clubUser->position === 'Adviser' || $clubUser->role === 'adviser')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-green-800">Adviser Authority</h3>
                                        <p class="text-green-700 mt-1">You can certify renewal applications and submit them to the administration.</p>
                                        <ul class="text-sm text-green-600 mt-2 list-disc list-inside">
                                            <li>Review prepared renewal applications</li>
                                            <li>Certify and approve applications</li>
                                            <li>Submit certified applications to admin</li>
                                            <li>Track final approval status</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                            </div>
                        </div>

                        <!-- Current Renewal Applications -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Current Renewal Applications</h2>
                                <p class="text-gray-600 mt-1">Active renewal applications for your club</p>
                            </div>
                    
                    <div class="p-6">
                        @if($renewals->count() > 0)
                            <div class="space-y-4">
                                @foreach($renewals as $renewal)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $renewal->academic_year }} Renewal</h3>
                                                <p class="text-sm text-gray-600">Submitted: {{ $renewal->submitted_at ? $renewal->submitted_at->format('M j, Y g:i A') : $renewal->created_at->format('M j, Y g:i A') }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $renewal->next_action }}</p>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    @if($renewal->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($renewal->status === 'pending_internal') bg-yellow-100 text-yellow-800
                                                    @elseif($renewal->status === 'pending_admin') bg-blue-100 text-blue-800
                                                    @elseif($renewal->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($renewal->status === 'rejected') bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $renewal->status_label }}
                                                </span>

                                                <a href="{{ route('club.officer.renewal.details', $renewal->id) }}"
                                                   class="px-3 py-1 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded text-xs transition-colors">
                                                    View Details
                                                </a>

                                                @if(($clubUser->position === 'President' || $clubUser->position === 'Vice President') && !$renewal->prepared_by_president && in_array($renewal->status, ['draft', 'pending_internal']))
                                                    <button onclick="confirmAction('prepare', {{ $renewal->id }}, '{{ $renewal->academic_year }}')"
                                                            class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs transition-colors">
                                                        Prepare
                                                    </button>
                                                @endif

                                                @if(($clubUser->position === 'Adviser' || $clubUser->role === 'adviser') && $renewal->prepared_by_president && !$renewal->certified_by_adviser && in_array($renewal->status, ['pending_internal']))
                                                    <button onclick="confirmAction('certify', {{ $renewal->id }}, '{{ $renewal->academic_year }}')"
                                                            class="px-3 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded text-xs transition-colors">
                                                        Certify
                                                    </button>
                                                @endif

                                                @if(in_array($renewal->status, ['draft', 'pending_internal', 'pending_admin']))
                                                    <button onclick="confirmAction('remove', {{ $renewal->id }}, '{{ $renewal->academic_year }}')"
                                                            class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs transition-colors">
                                                        Remove Application
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Detailed Progress Tracker -->
                                        <div class="mt-4 border-t pt-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Approval Progress</h4>
                                            
                                            <!-- Progress Steps -->
                                            <div class="space-y-3">
                                                <!-- Step 1: President Preparation -->
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                        {{ $renewal->prepared_by_president ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                                        @if($renewal->prepared_by_president)
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            1
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm font-medium text-gray-900">President Preparation</p>
                                                            @if($renewal->prepared_by_president)
                                                                <span class="text-xs text-green-600 font-medium">Completed</span>
                                                            @else
                                                                <span class="text-xs text-yellow-600 font-medium">Pending</span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-500">
                                                            @if($renewal->prepared_by_president)
                                                                Prepared by {{ $renewal->prepared_by_president_user }} on {{ $renewal->prepared_by_president_at->format('M j, Y') }}
                                                            @else
                                                                President needs to prepare and confirm the renewal application
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Step 2: Adviser Certification -->
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                        {{ $renewal->certified_by_adviser ? 'bg-green-500 text-white' : ($renewal->prepared_by_president ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                        @if($renewal->certified_by_adviser)
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            2
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm font-medium text-gray-900">Adviser Certification</p>
                                                            @if($renewal->certified_by_adviser)
                                                                <span class="text-xs text-green-600 font-medium">Completed</span>
                                                            @elseif($renewal->prepared_by_president)
                                                                <span class="text-xs text-blue-600 font-medium">Active</span>
                                                            @else
                                                                <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-500">
                                                            @if($renewal->certified_by_adviser)
                                                                Certified by {{ $renewal->certified_by_adviser_user }} on {{ $renewal->certified_by_adviser_at->format('M j, Y') }}
                                                            @elseif($renewal->prepared_by_president)
                                                                Faculty adviser needs to review and certify the application
                                                            @else
                                                                Waiting for president preparation to complete
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Admin Approval Section Header -->
                                                @if($renewal->prepared_by_president && $renewal->certified_by_adviser)
                                                    <div class="pt-2 border-t border-gray-100">
                                                        <p class="text-xs font-medium text-gray-700 mb-3">Administrative Approval Process</p>
                                                    </div>

                                                    <!-- Step 3: PSG Council Review -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                            {{ $renewal->reviewed_by_psg ? 'bg-green-500 text-white' : ($renewal->status === 'pending_admin' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                            @if($renewal->reviewed_by_psg)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                3
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-sm font-medium text-gray-900">PSG Council Review</p>
                                                                @if($renewal->reviewed_by_psg)
                                                                    <span class="text-xs text-green-600 font-medium">Completed</span>
                                                                @elseif($renewal->status === 'pending_admin')
                                                                    <span class="text-xs text-blue-600 font-medium">In Review</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-xs text-gray-500">
                                                                @if($renewal->reviewed_by_psg)
                                                                    Reviewed by {{ $renewal->reviewed_by_psg_user }} on {{ $renewal->reviewed_by_psg_at->format('M j, Y') }}
                                                                @elseif($renewal->status === 'pending_admin')
                                                                    Currently under review by PSG Council Adviser
                                                                @else
                                                                    Pending internal certification completion
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Step 4: Dean Noting -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                            {{ $renewal->noted_by_dean ? 'bg-green-500 text-white' : ($renewal->reviewed_by_psg ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                            @if($renewal->noted_by_dean)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                4
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-sm font-medium text-gray-900">Dean Noting</p>
                                                                @if($renewal->noted_by_dean)
                                                                    <span class="text-xs text-green-600 font-medium">Completed</span>
                                                                @elseif($renewal->reviewed_by_psg)
                                                                    <span class="text-xs text-blue-600 font-medium">In Review</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-xs text-gray-500">
                                                                @if($renewal->noted_by_dean)
                                                                    Noted by {{ $renewal->noted_by_dean_user }} on {{ $renewal->noted_by_dean_at->format('M j, Y') }}
                                                                @elseif($renewal->reviewed_by_psg)
                                                                    Currently under review by Dean
                                                                @else
                                                                    Waiting for PSG Council review
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Step 5: Director Endorsement -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                            {{ $renewal->endorsed_by_osa ? 'bg-green-500 text-white' : ($renewal->noted_by_dean ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                            @if($renewal->endorsed_by_osa)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                5
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-sm font-medium text-gray-900">Director Endorsement</p>
                                                                @if($renewal->endorsed_by_osa)
                                                                    <span class="text-xs text-green-600 font-medium">Completed</span>
                                                                @elseif($renewal->noted_by_dean)
                                                                    <span class="text-xs text-blue-600 font-medium">In Review</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-xs text-gray-500">
                                                                @if($renewal->endorsed_by_osa)
                                                                    Endorsed by {{ $renewal->endorsed_by_osa_user }} on {{ $renewal->endorsed_by_osa_at->format('M j, Y') }}
                                                                @elseif($renewal->noted_by_dean)
                                                                    Currently under review by Director of Student Affairs
                                                                @else
                                                                    Waiting for Dean noting
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Step 6: VP Final Approval -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex items-center justify-center w-6 h-6 rounded-full flex-shrink-0
                                                            {{ $renewal->approved_by_vp ? 'bg-green-500 text-white' : ($renewal->endorsed_by_osa ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                                            @if($renewal->approved_by_vp)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                6
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-sm font-medium text-gray-900">VP Academics Final Approval</p>
                                                                @if($renewal->approved_by_vp)
                                                                    <span class="text-xs text-green-600 font-medium">Approved</span>
                                                                @elseif($renewal->endorsed_by_osa)
                                                                    <span class="text-xs text-blue-600 font-medium">Final Review</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-xs text-gray-500">
                                                                @if($renewal->approved_by_vp)
                                                                    Final approval by {{ $renewal->approved_by_vp_user }} on {{ $renewal->approved_by_vp_at->format('M j, Y') }}
                                                                @elseif($renewal->endorsed_by_osa)
                                                                    Currently awaiting final approval by VP Academics
                                                                @else
                                                                    Waiting for Director endorsement
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Rejection Status -->
                                                @if($renewal->status === 'rejected')
                                                    <div class="pt-2 border-t border-red-100">
                                                        <div class="flex items-center space-x-2 text-red-600">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium">Application Rejected</span>
                                                        </div>
                                                        @if($renewal->rejection_reason)
                                                            <p class="text-xs text-red-500 mt-1">Reason: {{ $renewal->rejection_reason }}</p>
                                                        @endif
                                                        <p class="text-xs text-red-500 mt-1">Rejected on {{ $renewal->rejected_at->format('M j, Y g:i A') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Renewal Applications</h3>
                                <p class="text-gray-500 mb-4">There are currently no renewal applications for your club.</p>
                                <a href="{{ route('club.officer.renewal') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Submit New Renewal
                                </a>
                            </div>
                        @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Sidebar -->
                    <div class="lg:col-span-1 space-y-8">
                        
                        <!-- Renewal Process Guide -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Renewal Process</h2>
                                <p class="text-gray-600 mt-1">How the renewal approval process works</p>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Submit Application</h3>
                                            <p class="text-gray-600 text-sm">Officers submit renewal application with required documents</p>
                                        </div>
                                    </div>
                                    
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">President/Vice President Preparation</h3>
                                        <p class="text-gray-600 text-sm">President or Vice President reviews and prepares the application for adviser certification</p>
                                    </div>
                                </div>                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Adviser Certification</h3>
                                            <p class="text-gray-600 text-sm">Faculty adviser certifies and submits to administration</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold">4</div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Admin Approval</h3>
                                            <p class="text-gray-600 text-sm">Administration reviews and provides final approval</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
                                <p class="text-gray-600 mt-1">Common renewal-related actions</p>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-4">
                                    <a href="{{ route('club.officer.renewal') }}" 
                                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Submit New Renewal</h3>
                                            <p class="text-gray-600 text-sm">Start a new renewal application</p>
                                        </div>
                                    </a>
                                    
                                    <a href="{{ route('club.officer.dashboard') }}" 
                                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Back to Dashboard</h3>
                                            <p class="text-gray-600 text-sm">Return to main dashboard</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Action Confirmation Modal -->
    <div id="actionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Confirm Action</h3>
                    <button onclick="closeActionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4" id="modalMessage">
                        <!-- Dynamic message will be inserted here -->
                    </p>
                    
                    <div class="mb-4">
                        <label for="actionPassword" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your password to confirm:
                        </label>
                        <input type="password" 
                               id="actionPassword" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Your password">
                        <div id="actionPasswordError" class="text-red-600 text-xs mt-1 hidden"></div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeActionModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button onclick="executeAction()" 
                            id="actionButton"
                            class="px-4 py-2 rounded-md transition-colors">
                        <!-- Dynamic button text will be inserted here -->
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAction = null;
        let renewalToProcess = null;

        function confirmAction(action, renewalId, academicYear) {
            currentAction = action;
            renewalToProcess = renewalId;
            
            const modal = document.getElementById('actionModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const button = document.getElementById('actionButton');
            
            // Reset form
            document.getElementById('actionPassword').value = '';
            document.getElementById('actionPasswordError').classList.add('hidden');
            
            // Configure modal based on action
            switch(action) {
                case 'remove':
                    title.textContent = 'Remove Application';
                    message.innerHTML = `Are you sure you want to remove the <strong>${academicYear}</strong> renewal application? This action cannot be undone and will permanently delete all associated files.`;
                    button.textContent = 'Remove Application';
                    button.className = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors';
                    break;
                case 'prepare':
                    title.textContent = 'Prepare Application';
                    message.innerHTML = `Are you sure you want to prepare the <strong>${academicYear}</strong> renewal application? This will mark it as ready for adviser certification.`;
                    button.textContent = 'Prepare Application';
                    button.className = 'px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors';
                    break;
                case 'certify':
                    title.textContent = 'Certify Application';
                    message.innerHTML = `Are you sure you want to certify the <strong>${academicYear}</strong> renewal application? This will submit it to the administration for approval.`;
                    button.textContent = 'Certify Application';
                    button.className = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors';
                    break;
            }
            
            modal.classList.remove('hidden');
            document.getElementById('actionPassword').focus();
        }

        function closeActionModal() {
            document.getElementById('actionModal').classList.add('hidden');
            currentAction = null;
            renewalToProcess = null;
        }

        function executeAction() {
            const password = document.getElementById('actionPassword').value;
            const errorDiv = document.getElementById('actionPasswordError');
            const actionButton = document.getElementById('actionButton');

            if (!password) {
                errorDiv.textContent = 'Password is required';
                errorDiv.classList.remove('hidden');
                return;
            }

            // Disable button and show loading
            actionButton.disabled = true;
            const originalText = actionButton.textContent;
            actionButton.textContent = 'Processing...';

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            
            // Set action URL based on current action
            switch(currentAction) {
                case 'remove':
                    form.action = `/club/officer/renewal/${renewalToProcess}/delete`;
                    break;
                case 'prepare':
                    form.action = `/club/officer/renewal/${renewalToProcess}/prepare`;
                    break;
                case 'certify':
                    form.action = `/club/officer/renewal/${renewalToProcess}/certify`;
                    break;
            }

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add method override for DELETE if removing
            if (currentAction === 'remove') {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
            }

            // Add password
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;
            form.appendChild(passwordInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeActionModal();
            }
        });

        // Close modal on outside click
        document.getElementById('actionModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeActionModal();
            }
        });
    </script>
</body>
</html>
