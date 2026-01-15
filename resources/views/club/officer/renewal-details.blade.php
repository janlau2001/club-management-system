<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Renewal Details - {{ $club->name }}</title>
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
                    <h1 class="text-2xl font-bold text-white">Renewal Details</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $renewal->academic_year }}</p>
                    <p class="text-white opacity-75 text-sm">{{ $clubUser->name }} ({{ $clubUser->position ?? 'Officer' }})</p>
                </div>
                <a href="{{ route('club.officer.renewal.status') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Back to Status
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
            <div class="max-w-6xl mx-auto space-y-8">
                
                <!-- Status Overview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $renewal->academic_year }} Renewal Application</h2>
                                <p class="text-gray-600 mt-1">Submitted: {{ $renewal->submitted_at ? $renewal->submitted_at->format('M j, Y g:i A') : $renewal->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-medium
                                @if($renewal->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($renewal->status === 'pending_internal') bg-yellow-100 text-yellow-800
                                @elseif($renewal->status === 'pending_admin') bg-blue-100 text-blue-800
                                @elseif($renewal->status === 'approved') bg-green-100 text-green-800
                                @elseif($renewal->status === 'rejected') bg-red-100 text-red-800
                                @endif">
                                {{ $renewal->status_label }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Comprehensive Progress Timeline -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Renewal Approval Progress</h3>
                            
                            <!-- Progress Steps -->
                            <div class="space-y-4">
                                <!-- Step 1: Application Submitted -->
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                            <span class="text-xs text-green-600 font-medium">Completed</span>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            Submitted on {{ $renewal->submitted_at ? $renewal->submitted_at->format('M j, Y g:i A') : $renewal->created_at->format('M j, Y g:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 2: President Preparation -->
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                        {{ $renewal->prepared_by_president ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
                                        @if($renewal->prepared_by_president)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            2
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">President Preparation</p>
                                            @if($renewal->prepared_by_president)
                                                <span class="text-xs text-green-600 font-medium">Completed</span>
                                            @else
                                                <span class="text-xs text-blue-600 font-medium">Action Required</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            @if($renewal->prepared_by_president)
                                                Prepared by {{ $renewal->prepared_by_president_user }} on {{ $renewal->prepared_by_president_at->format('M j, Y g:i A') }}
                                            @else
                                                President needs to prepare and confirm the renewal application
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3: Adviser Certification -->
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                        {{ $renewal->certified_by_adviser ? 'bg-green-500 text-white' : ($renewal->prepared_by_president ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                        @if($renewal->certified_by_adviser)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            3
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Adviser Certification</p>
                                            @if($renewal->certified_by_adviser)
                                                <span class="text-xs text-green-600 font-medium">Completed</span>
                                            @elseif($renewal->prepared_by_president)
                                                <span class="text-xs text-blue-600 font-medium">Action Required</span>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium">Waiting</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            @if($renewal->certified_by_adviser)
                                                Certified by {{ $renewal->certified_by_adviser_user }} on {{ $renewal->certified_by_adviser_at->format('M j, Y g:i A') }}
                                            @elseif($renewal->prepared_by_president)
                                                Faculty adviser needs to review and certify the application
                                            @else
                                                Waiting for president preparation to complete
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Administrative Approval Section -->
                                @if($renewal->prepared_by_president && $renewal->certified_by_adviser)
                                    <div class="pt-3 border-t border-gray-100">
                                        <p class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Administrative Approval Process
                                        </p>
                                    </div>

                                    <!-- Step 4: PSG Council Review -->
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                            {{ $renewal->reviewed_by_psg ? 'bg-green-500 text-white' : ($renewal->status === 'pending_admin' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                            @if($renewal->reviewed_by_psg)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                4
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
                                                    Reviewed by {{ $renewal->reviewed_by_psg_user }} on {{ $renewal->reviewed_by_psg_at->format('M j, Y g:i A') }}
                                                @elseif($renewal->status === 'pending_admin')
                                                    Currently under review by PSG Council Adviser
                                                @else
                                                    Will be reviewed by PSG Council Adviser
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 5: Dean Noting -->
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                            {{ $renewal->noted_by_dean ? 'bg-green-500 text-white' : ($renewal->reviewed_by_psg ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                            @if($renewal->noted_by_dean)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                5
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
                                                    <span class="text-xs text-gray-400 font-medium">Pending</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                @if($renewal->noted_by_dean)
                                                    Noted by {{ $renewal->noted_by_dean_user }} on {{ $renewal->noted_by_dean_at->format('M j, Y g:i A') }}
                                                @elseif($renewal->reviewed_by_psg)
                                                    Currently under review by Dean
                                                @else
                                                    Awaiting PSG Council review completion
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 6: Director Endorsement -->
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                            {{ $renewal->endorsed_by_osa ? 'bg-green-500 text-white' : ($renewal->noted_by_dean ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                            @if($renewal->endorsed_by_osa)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                6
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
                                                    <span class="text-xs text-gray-400 font-medium">Pending</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                @if($renewal->endorsed_by_osa)
                                                    Endorsed by {{ $renewal->endorsed_by_osa_user }} on {{ $renewal->endorsed_by_osa_at->format('M j, Y g:i A') }}
                                                @elseif($renewal->noted_by_dean)
                                                    Currently under review by Director of Student Affairs
                                                @else
                                                    Awaiting Dean noting completion
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Step 7: VP Final Approval -->
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                            {{ $renewal->approved_by_vp ? 'bg-green-500 text-white' : ($renewal->endorsed_by_osa ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                            @if($renewal->approved_by_vp)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                7
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">VP Academics Final Approval</p>
                                                @if($renewal->approved_by_vp)
                                                    <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">✅ APPROVED</span>
                                                @elseif($renewal->endorsed_by_osa)
                                                    <span class="text-xs text-blue-600 font-medium">Final Review</span>
                                                @else
                                                    <span class="text-xs text-gray-400 font-medium">Pending</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                @if($renewal->approved_by_vp)
                                                    🎉 Final approval granted by {{ $renewal->approved_by_vp_user }} on {{ $renewal->approved_by_vp_at->format('M j, Y g:i A') }}
                                                @elseif($renewal->endorsed_by_osa)
                                                    Currently awaiting final approval by VP Academics
                                                @else
                                                    Awaiting Director endorsement completion
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Rejection Status -->
                                @if($renewal->status === 'rejected')
                                    <div class="pt-3 border-t border-red-100">
                                        <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white flex-shrink-0">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-red-800">Application Rejected</p>
                                                @if($renewal->rejection_reason)
                                                    <p class="text-xs text-red-600 mt-1"><strong>Reason:</strong> {{ $renewal->rejection_reason }}</p>
                                                @endif
                                                <p class="text-xs text-red-600 mt-1">Rejected on {{ $renewal->rejected_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Application Information</h2>
                        <p class="text-gray-600 mt-1">Details of the renewal application</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                                <p class="text-gray-900">{{ $renewal->academic_year }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <p class="text-gray-900">{{ $renewal->department }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nature of Organization</label>
                                <p class="text-gray-900">{{ $renewal->nature }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Faculty Adviser</label>
                                <p class="text-gray-900">{{ $renewal->faculty_adviser }}</p>
                            </div>
                            @if($renewal->last_renewal_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Renewal Date</label>
                                    <p class="text-gray-900">{{ $renewal->last_renewal_date->format('M j, Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Rationale -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brief Rationale of the Club</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $renewal->rationale }}</p>
                            </div>
                        </div>

                        <!-- Uploaded Documents -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Uploaded Documents</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($renewal->officers_list_file)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">List of New Officers</p>
                                                <p class="text-sm text-gray-500">Uploaded</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($renewal->activities_plan_file)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">Program of Activities</p>
                                                <p class="text-sm text-gray-500">Uploaded</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($renewal->budget_proposal_file)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">Budget Proposal</p>
                                                <p class="text-sm text-gray-500">Uploaded</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($renewal->constitution_file)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">Constitution & By-laws</p>
                                                <p class="text-sm text-gray-500">Uploaded</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($clubUser->position === 'President' && $renewal->canBePrepared())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">President Action Required</h2>
                            <p class="text-gray-600 mt-1">Prepare this renewal application for adviser certification</p>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('club.officer.renewal.prepare', $renewal->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Enter your password to confirm preparation <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="current_password" id="current_password" required
                                           class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Your current password">
                                </div>
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Prepare Renewal Application
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if(($clubUser->position === 'Adviser' || $clubUser->role === 'adviser') && $renewal->canBeCertified())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Adviser Action Required</h2>
                            <p class="text-gray-600 mt-1">Certify this renewal application and submit to administration</p>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('club.officer.renewal.certify', $renewal->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="current_password_adviser" class="block text-sm font-medium text-gray-700 mb-2">
                                        Enter your password to confirm certification <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="current_password" id="current_password_adviser" required
                                           class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Your current password">
                                </div>
                                <button type="submit" 
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    Certify Renewal Application
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Confirmation Modal for Prepare -->
    <div id="prepareConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 class="text-lg font-medium text-gray-900 text-center">Confirm Preparation</h3>
                    <p class="text-sm text-gray-600 mt-2 text-center">
                        Are you sure you want to prepare this renewal application? This action cannot be undone.
                    </p>
                    <div class="flex items-center justify-center space-x-3 mt-4">
                        <button type="button" onclick="hidePrepareConfirmModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="confirmPrepare()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Yes, Prepare
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Certify -->
    <div id="certifyConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 class="text-lg font-medium text-gray-900 text-center">Confirm Certification</h3>
                    <p class="text-sm text-gray-600 mt-2 text-center">
                        Are you sure you want to certify this renewal application? This will submit it to the administration for final approval.
                    </p>
                    <div class="flex items-center justify-center space-x-3 mt-4">
                        <button type="button" onclick="hideCertifyConfirmModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="confirmCertify()" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Yes, Certify
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let prepareForm = null;
        let certifyForm = null;

        function showPrepareConfirmModal(event) {
            event.preventDefault();
            prepareForm = event.target;
            document.getElementById('prepareConfirmModal').classList.remove('hidden');
            return false;
        }

        function hidePrepareConfirmModal() {
            document.getElementById('prepareConfirmModal').classList.add('hidden');
            prepareForm = null;
        }

        function confirmPrepare() {
            if (prepareForm) {
                prepareForm.onsubmit = null; // Remove the event handler
                prepareForm.submit();
            }
        }

        function showCertifyConfirmModal(event) {
            event.preventDefault();
            certifyForm = event.target;
            document.getElementById('certifyConfirmModal').classList.remove('hidden');
            return false;
        }

        function hideCertifyConfirmModal() {
            document.getElementById('certifyConfirmModal').classList.add('hidden');
            certifyForm = null;
        }

        function confirmCertify() {
            if (certifyForm) {
                certifyForm.onsubmit = null; // Remove the event handler
                certifyForm.submit();
            }
        }

        // Attach event handlers on page load
        document.addEventListener('DOMContentLoaded', function() {
            const prepareForms = document.querySelectorAll('form[action*="renewal.prepare"]');
            prepareForms.forEach(form => {
                form.onsubmit = showPrepareConfirmModal;
            });

            const certifyForms = document.querySelectorAll('form[action*="renewal.certify"]');
            certifyForms.forEach(form => {
                form.onsubmit = showCertifyConfirmModal;
            });
        });
    </script>
</body>
</html>
