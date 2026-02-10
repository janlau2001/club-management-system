<x-dashboard-layout>
    <x-slot name="title">Renewal Details - {{ $renewal->club->name }}</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Renewal Details</h1>
            <p class="text-gray-600 mt-2">{{ $renewal->club->name }} • {{ $renewal->academic_year }}</p>
        </div>
        <a href="{{ route('head-office.renewals') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Renewals</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Status Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $renewal->academic_year }} Renewal Application</h2>
                            <p class="text-gray-600 text-sm mt-1">Submitted on {{ $renewal->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="text-right">
                            @if($renewal->status === 'pending_dean')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending Dean Review
                                </span>
                            @elseif($renewal->status === 'pending_vp')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending VP Review
                                </span>
                            @elseif($renewal->status === 'pending_director')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending Director Review
                                </span>
                            @elseif($renewal->status === 'pending_psg')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending PSG Council Review
                                </span>
                            @elseif($renewal->status === 'pending_admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending Admin Review
                                </span>
                            @elseif($renewal->status === 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approved
                                </span>
                            @elseif($renewal->status === 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Academic Year</label>
                                    <p class="text-gray-900">{{ $renewal->academic_year }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Submission Date</label>
                                    <p class="text-gray-900">{{ $renewal->created_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Current Status</label>
                                    <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $renewal->status) }}</p>
                                </div>
                                @if($renewal->approved_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Approved Date</label>
                                    <p class="text-gray-900">{{ $renewal->approved_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Club Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Organization Name</label>
                                    <p class="text-gray-900">{{ $renewal->club->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Department</label>
                                    <p class="text-gray-900">{{ $renewal->club->department }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Club Type</label>
                                    <p class="text-gray-900">{{ $renewal->club->club_type }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Current Status</label>
                                    <p class="text-gray-900 capitalize">{{ $renewal->club->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Approval Progress</h2>
                    <p class="text-gray-600 text-sm mt-1">Track the progress of the renewal application</p>
                </div>
                <div class="p-6">
                    <!-- Progress Steps -->
                    <div class="space-y-4">
                        <!-- Step 1: President Preparation -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                {{ $renewal->prepared_by_president ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                @if($renewal->prepared_by_president)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if($renewal->prepared_by_president)
                                        Prepared by {{ $renewal->prepared_by_president_user }} on {{ $renewal->prepared_by_president_at->format('M j, Y g:i A') }}
                                    @else
                                        Club president needs to prepare and submit the renewal application
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: Adviser Certification -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                {{ $renewal->certified_by_adviser ? 'bg-green-500 text-white' : ($renewal->prepared_by_president ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                @if($renewal->certified_by_adviser)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                    @elseif($renewal->prepared_by_president)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waiting</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
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

                        <!-- Admin Approval Section Header -->
                        @if($renewal->prepared_by_president && $renewal->certified_by_adviser)
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm font-medium text-gray-700 mb-4">Administrative Approval Process</p>
                            </div>

                            <!-- Step 3: PSG Council Review -->
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                    {{ $renewal->reviewed_by_psg ? 'bg-green-500 text-white' : ($renewal->status === 'pending_admin' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    @if($renewal->reviewed_by_psg)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                        @elseif($renewal->status === 'pending_admin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Review</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waiting</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($renewal->reviewed_by_psg)
                                            Reviewed by {{ $renewal->reviewed_by_psg_user }} on {{ $renewal->reviewed_by_psg_at->format('M j, Y g:i A') }}
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
                                <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                    {{ $renewal->noted_by_dean ? 'bg-green-500 text-white' : ($renewal->reviewed_by_psg ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    @if($renewal->noted_by_dean)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                        @elseif($renewal->reviewed_by_psg)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Review</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waiting</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($renewal->noted_by_dean)
                                            Noted by {{ $renewal->noted_by_dean_user }} on {{ $renewal->noted_by_dean_at->format('M j, Y g:i A') }}
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
                                <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                    {{ $renewal->endorsed_by_osa ? 'bg-green-500 text-white' : ($renewal->noted_by_dean ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    @if($renewal->endorsed_by_osa)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                        @elseif($renewal->noted_by_dean)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Review</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waiting</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($renewal->endorsed_by_osa)
                                            Endorsed by {{ $renewal->endorsed_by_osa_user }} on {{ $renewal->endorsed_by_osa_at->format('M j, Y g:i A') }}
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
                                <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                    {{ $renewal->approved_by_vp ? 'bg-green-500 text-white' : ($renewal->endorsed_by_osa ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                    @if($renewal->approved_by_vp)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                        @elseif($renewal->endorsed_by_osa)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Final Review</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waiting</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($renewal->approved_by_vp)
                                            Approved by {{ $renewal->approved_by_vp_user }} on {{ $renewal->approved_by_vp_at->format('M j, Y g:i A') }}
                                        @elseif($renewal->endorsed_by_osa)
                                            Currently awaiting final approval by VP Academics
                                        @else
                                            Waiting for Director endorsement
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Head Office Final Approval (only show if all admin approvals are complete) -->
                            @if($renewal->approved_by_vp && $renewal->endorsed_by_osa && $renewal->noted_by_dean && $renewal->reviewed_by_psg)
                                <div class="pt-4 border-t border-gray-100">
                                    <p class="text-sm font-medium text-gray-700 mb-4">Head of Student Affairs Final Approval</p>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0
                                        {{ $renewal->status === 'approved' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
                                        @if($renewal->status === 'approved')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            7
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">Head Office Final Approval</p>
                                            @if($renewal->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ APPROVED</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Ready for Approval</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            @if($renewal->status === 'approved')
                                                🎉 Final approval granted by {{ $renewal->final_approved_by ?? $renewal->approved_by }} on {{ $renewal->final_approved_at ? $renewal->final_approved_at->format('M j, Y g:i A') : ($renewal->approved_at ? $renewal->approved_at->format('M j, Y g:i A') : 'Unknown date') }}
                                                <br><span class="text-green-600 font-medium">Renewal cycle has been reset. Club is now renewed for another year.</span>
                                            @else
                                                Ready for final approval by Head of Student Affairs
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Rejection Status -->
                        @if($renewal->status === 'rejected')
                            <div class="pt-4 border-t border-red-100">
                                <div class="flex items-center space-x-2 text-red-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-lg font-medium">Application Rejected</span>
                                </div>
                                @if($renewal->rejection_reason)
                                    <p class="text-sm text-red-600 mt-2">Reason: {{ $renewal->rejection_reason }}</p>
                                @endif
                                <p class="text-sm text-red-600 mt-1">Rejected on {{ $renewal->rejected_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @php
                        // Check if all administrative approvals are complete
                        $allAdminApprovalsComplete = $renewal->reviewed_by_psg && 
                                                   $renewal->noted_by_dean && 
                                                   $renewal->endorsed_by_osa && 
                                                   $renewal->approved_by_vp;
                    @endphp
                    
                    @if($renewal->status === 'approved')
                        <div class="w-full bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm font-medium text-center">
                            ✅ Renewal Approved
                        </div>
                    @elseif($renewal->status === 'rejected')
                        <div class="w-full bg-red-100 text-red-800 px-4 py-2 rounded-lg text-sm font-medium text-center">
                            ❌ Renewal Rejected
                        </div>
                    @else
                        <!-- Always show approve/reject buttons, but enable/disable based on admin approvals -->
                        <button onclick="@if($allAdminApprovalsComplete)approveRenewal({{ $renewal->id }})@else showPendingMessage()@endif" 
                                class="w-full px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center space-x-2
                                    {{ $allAdminApprovalsComplete 
                                        ? 'bg-green-600 hover:bg-green-700 text-white cursor-pointer' 
                                        : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $allAdminApprovalsComplete ? 'Approve Renewal' : 'Approve Renewal (Disabled)' }}</span>
                        </button>
                        
                        <button onclick="rejectRenewal({{ $renewal->id }})" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Reject Renewal</span>
                        </button>
                        
                        @if(!$allAdminApprovalsComplete)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs text-yellow-700 text-center font-medium">
                                    ⏳ Approve Button Disabled - Waiting for Administrative Approvals
                                </p>
                                <p class="text-xs text-yellow-600 text-center mt-1">
                                    @php
                                        $pending = [];
                                        if (!$renewal->reviewed_by_psg) $pending[] = 'PSG Council Review';
                                        if (!$renewal->noted_by_dean) $pending[] = 'Dean Noting';
                                        if (!$renewal->endorsed_by_osa) $pending[] = 'Director Endorsement';
                                        if (!$renewal->approved_by_vp) $pending[] = 'VP Academics Approval';
                                    @endphp
                                    Pending: {{ implode(', ', $pending) }}
                                </p>
                                <p class="text-xs text-green-600 text-center mt-1 font-medium">
                                    💡 You can reject the renewal at any time if needed
                                </p>
                            </div>
                        @else
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="text-xs text-green-700 text-center font-medium">
                                    ✅ All Administrative Approvals Complete
                                </p>
                                <p class="text-xs text-green-600 text-center mt-1">
                                    Ready for Head of Student Affairs final approval
                                </p>
                            </div>
                        @endif
                    @endif
                    
                    <a href="{{ route('head-office.organization.show', $renewal->club) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-block text-center">
                        View Organization
                    </a>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="space-y-3">
                    @if($renewal->club->clubUsers->where('role', 'president')->first())
                        @php $president = $renewal->club->clubUsers->where('role', 'president')->first(); @endphp
                        <div>
                            <label class="text-sm font-medium text-gray-500">President</label>
                            <p class="text-gray-900">{{ $president->name }}</p>
                            @if($president->email)
                                <p class="text-sm text-gray-600">{{ $president->email }}</p>
                            @endif
                        </div>
                    @endif
                    
                    @if($renewal->club->clubUsers->where('role', 'adviser')->first())
                        @php $adviser = $renewal->club->clubUsers->where('role', 'adviser')->first(); @endphp
                        <div>
                            <label class="text-sm font-medium text-gray-500">Adviser</label>
                            <p class="text-gray-900">{{ $adviser->name }}</p>
                            @if($adviser->email)
                                <p class="text-sm text-gray-600">{{ $adviser->email }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-dashboard-layout>

<!-- Modal HTML Components -->
<!-- Password Authentication Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="passwordModalTitle">Authentication Required</h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 mb-4" id="passwordModalMessage">Please enter your password to continue:</p>
                <input type="password" id="passwordInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password">
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closePasswordModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button onclick="submitPassword()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Rejection Reason</h3>
                <button onclick="closeRejectionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 mb-4">Please provide a reason for rejecting this renewal application:</p>
                <textarea id="rejectionReasonInput" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter rejection reason..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeRejectionModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button onclick="submitRejectionReason()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="successModalTitle">Success!</h3>
                <p class="text-gray-600 mb-4" id="successModalMessage">Action completed successfully.</p>
                <button onclick="closeSuccessModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Error</h3>
                <p class="text-gray-600 mb-4" id="errorModalMessage">An error occurred.</p>
                <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div id="infoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Information</h3>
                <p class="text-gray-600 mb-4" id="infoModalMessage">Information message.</p>
                <button onclick="closeInfoModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal control functions
let currentAction = null;
let currentRenewalId = null;
let adminInfo = null;

function showPasswordModal(title, message, action, renewalId, adminData) {
    currentAction = action;
    currentRenewalId = renewalId;
    adminInfo = adminData;
    
    document.getElementById('passwordModalTitle').textContent = title;
    document.getElementById('passwordModalMessage').textContent = message;
    document.getElementById('passwordInput').value = '';
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordInput').focus();
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    currentAction = null;
    currentRenewalId = null;
    adminInfo = null;
}

function submitPassword() {
    const password = document.getElementById('passwordInput').value.trim();
    if (!password) {
        showErrorModal('Please enter your password.');
        return;
    }
    
    closePasswordModal();
    
    if (currentAction === 'approve') {
        performApproval(currentRenewalId, password);
    } else if (currentAction === 'reject') {
        performRejection(currentRenewalId, password);
    }
}

function showRejectionModal(renewalId) {
    currentRenewalId = renewalId;
    document.getElementById('rejectionReasonInput').value = '';
    document.getElementById('rejectionModal').classList.remove('hidden');
    document.getElementById('rejectionReasonInput').focus();
}

function closeRejectionModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
    currentRenewalId = null;
}

function submitRejectionReason() {
    const reason = document.getElementById('rejectionReasonInput').value.trim();
    if (!reason) {
        showErrorModal('Please provide a reason for rejection.');
        return;
    }
    
    closeRejectionModal();
    
    // Get admin info and show password modal
    fetch('/head-office/admin-info', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Store rejection reason for later use
            currentRejectionReason = reason;
            showPasswordModal(
                'Authentication Required',
                `Hello ${data.name}, please enter your password to reject this renewal application:`,
                'reject',
                currentRenewalId,
                data
            );
        } else {
            showErrorModal('Could not retrieve admin information.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('An error occurred while retrieving admin information.');
    });
}

function showSuccessModal(title, message) {
    document.getElementById('successModalTitle').textContent = title;
    document.getElementById('successModalMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

function showErrorModal(message) {
    document.getElementById('errorModalMessage').textContent = message;
    document.getElementById('errorModal').classList.remove('hidden');
}

function closeErrorModal() {
    document.getElementById('errorModal').classList.add('hidden');
}

function showInfoModal(message) {
    document.getElementById('infoModalMessage').textContent = message;
    document.getElementById('infoModal').classList.remove('hidden');
}

function closeInfoModal() {
    document.getElementById('infoModal').classList.add('hidden');
}

// Keyboard event handlers
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePasswordModal();
        closeRejectionModal();
        closeSuccessModal();
        closeErrorModal();
        closeInfoModal();
    } else if (event.key === 'Enter') {
        if (!document.getElementById('passwordModal').classList.contains('hidden')) {
            submitPassword();
        } else if (!document.getElementById('rejectionModal').classList.contains('hidden')) {
            submitRejectionReason();
        }
    }
});

function approveRenewal(renewalId) {
    // Get admin info first
    fetch('/head-office/admin-info', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showPasswordModal(
                'Authentication Required',
                `Hello ${data.name}, please enter your password to approve this renewal application:`,
                'approve',
                renewalId,
                data
            );
        } else {
            showErrorModal('Could not retrieve admin information.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorModal('An error occurred while retrieving admin information.');
    });
}

function performApproval(renewalId, password) {
function performApproval(renewalId, password) {
    // Verify password first
    fetch('/head-office/verify-password', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            password: password
        })
    })
    .then(response => response.json())
    .then(verifyData => {
        if (verifyData.success) {
            // Password verified, proceed with approval
            fetch(`/head-office/renewals/${renewalId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    password: password
                })
            })
            .then(response => response.json())
            .then(approvalData => {
                if (approvalData.success) {
                    showSuccessModal('Success!', 'Renewal approved successfully!');
                    // Update the UI dynamically instead of reloading
                    updateApprovalStatus('approved');
                } else {
                    showErrorModal('Error: ' + approvalData.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('An error occurred while approving the renewal.');
            });
        } else {
            showErrorModal(verifyData.message);
        }
    })
    .catch(error => {
        console.error('Verification Error:', error);
        showErrorModal('An error occurred during password verification.');
    });
}

function rejectRenewal(renewalId) {
    showRejectionModal(renewalId);
}

let currentRejectionReason = null;

function performRejection(renewalId, password) {
    // Verify password first
    fetch('/head-office/verify-password', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            password: password
        })
    })
    .then(response => response.json())
    .then(verifyData => {
        if (verifyData.success) {
            // Password verified, proceed with rejection
            fetch(`/head-office/renewals/${renewalId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    password: password,
                    rejection_reason: currentRejectionReason
                })
            })
            .then(response => response.json())
            .then(rejectionData => {
                if (rejectionData.success) {
                    showSuccessModal('Success!', 'Renewal rejected successfully!');
                    // Update the UI dynamically instead of reloading
                    updateApprovalStatus('rejected');
                } else {
                    showErrorModal('Error: ' + rejectionData.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('An error occurred while rejecting the renewal.');
            });
        } else {
            showErrorModal(verifyData.message);
        }
    })
    .catch(error => {
        console.error('Verification Error:', error);
        showErrorModal('An error occurred during password verification.');
    });
}

function updateApprovalStatus(status) {
    // Update the Quick Actions section
    const quickActionsDiv = document.querySelector('.bg-white.rounded-xl.shadow-sm.border.border-gray-100.p-6 .space-y-3');
    if (quickActionsDiv) {
        if (status === 'approved') {
            quickActionsDiv.innerHTML = `
                <div class="w-full bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm font-medium text-center">
                    ✅ Renewal Approved
                </div>
                <a href="{{ route('head-office.organization.show', $renewal->club) }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-block text-center">
                    View Organization
                </a>
            `;
        } else if (status === 'rejected') {
            quickActionsDiv.innerHTML = `
                <div class="w-full bg-red-100 text-red-800 px-4 py-2 rounded-lg text-sm font-medium text-center">
                    ❌ Renewal Rejected
                </div>
                <a href="{{ route('head-office.organization.show', $renewal->club) }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-block text-center">
                    View Organization
                </a>
            `;
        }
    }

    // Show info message suggesting manual refresh for full updates
    if (status === 'approved') {
        setTimeout(() => {
            showInfoModal('💡 Tip: Refresh the page manually to see all updated information in the renewal timeline.');
        }, 1000);
    }
}

function showPendingMessage() {
    showInfoModal('⏳ Cannot perform this action yet. Please wait for all administrative approvals to be completed first.');
}
</script>
