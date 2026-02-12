<x-dashboard-layout>
    <x-slot name="title">{{ $registration->club_name }} - Registration Details</x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">{{ $registration->club_name }}</h1>
            <a href="{{ route('head-office.registrations') }}" class="text-blue-600 hover:text-blue-800">← Back to Registrations</a>
        </div>

        <!-- Club Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Club Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Club Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->club_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->department }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nature</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->nature }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recommended Adviser</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->recommended_adviser }}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Rationale</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->rationale }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Submitted At</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $registration->submitted_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $registration->status_badge }}">
                        {{ ucfirst($registration->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Submitted Documents -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Submitted Documents</h2>
            <div class="grid grid-cols-2 gap-4">
                @if($registration->constitution_file)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Constitution and By-Laws</label>
                    <a href="{{ Storage::url($registration->constitution_file) }}" target="_blank"
                       class="mt-1 text-sm text-blue-600 hover:text-blue-800">View Document</a>
                </div>
                @endif

                @if($registration->officers_list_file)
                <div>
                    <label class="block text-sm font-medium text-gray-700">List of Officers</label>
                    <a href="{{ Storage::url($registration->officers_list_file) }}" target="_blank"
                       class="mt-1 text-sm text-blue-600 hover:text-blue-800">View Document</a>
                </div>
                @endif

                @if($registration->activities_plan_file)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activities/Action Plan</label>
                    <a href="{{ Storage::url($registration->activities_plan_file) }}" target="_blank"
                       class="mt-1 text-sm text-blue-600 hover:text-blue-800">View Document</a>
                </div>
                @endif

                @if($registration->budget_proposal_file)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Budget Proposal</label>
                    <a href="{{ Storage::url($registration->budget_proposal_file) }}" target="_blank"
                       class="mt-1 text-sm text-blue-600 hover:text-blue-800">View Document</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Approval Status -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Approval Status</h2>
            <div class="space-y-4">
                <!-- Head Office Verification -->
                <div class="flex items-center justify-between p-4 border rounded-lg {{ $registration->verified_by_osa ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($registration->verified_by_osa)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $registration->verified_by_osa ? 'text-green-800' : 'text-gray-900' }}">
                                VERIFIED BY Head of Office of Student Affairs & PSG Advisers
                            </p>
                            @if($registration->verified_by_osa)
                                <p class="text-xs text-green-600">
                                    Verified on {{ $registration->verified_by_osa_at->format('M j, Y \a\t g:i A') }}
                                    @if($registration->verified_by_osa_user)
                                        by {{ $registration->verified_by_osa_user }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $registration->verified_by_osa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $registration->verified_by_osa ? 'Verified' : 'Pending' }}
                    </span>
                </div>

                <!-- Director Noting -->
                <div class="flex items-center justify-between p-4 border rounded-lg {{ $registration->noted_by_director ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($registration->noted_by_director)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $registration->noted_by_director ? 'text-green-800' : 'text-gray-900' }}">
                                NOTED BY Director, Student Affairs and Academic Support Services
                            </p>
                            @if($registration->noted_by_director)
                                <p class="text-xs text-green-600">
                                    Noted on {{ $registration->noted_by_director_at->format('M j, Y \a\t g:i A') }}
                                    @if($registration->noted_by_director_user)
                                        by {{ $registration->noted_by_director_user }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $registration->noted_by_director ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $registration->noted_by_director ? 'Noted' : 'Pending' }}
                    </span>
                </div>

                <!-- VP Approval -->
                <div class="flex items-center justify-between p-4 border rounded-lg {{ $registration->approved_by_vp ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($registration->approved_by_vp)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $registration->approved_by_vp ? 'text-green-800' : 'text-gray-900' }}">
                                APPROVED BY Vice President for Academics
                            </p>
                            @if($registration->approved_by_vp)
                                <p class="text-xs text-green-600">
                                    Approved on {{ $registration->approved_by_vp_at->format('M j, Y \a\t g:i A') }}
                                    @if($registration->approved_by_vp_user)
                                        by {{ $registration->approved_by_vp_user }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $registration->approved_by_vp ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $registration->approved_by_vp ? 'Approved' : 'Pending' }}
                    </span>
                </div>

                <!-- Dean Endorsement -->
                <div class="flex items-center justify-between p-4 border rounded-lg {{ $registration->endorsed_by_dean ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($registration->endorsed_by_dean)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $registration->endorsed_by_dean ? 'text-green-800' : 'text-gray-900' }}">
                                ENDORSED BY Dean
                            </p>
                            @if($registration->endorsed_by_dean && $registration->endorsed_by_dean_at)
                                <p class="text-xs text-green-600">
                                    Endorsed on {{ $registration->endorsed_by_dean_at->format('M j, Y \a\t g:i A') }}
                                    @if($registration->endorsed_by_dean_user)
                                        by {{ $registration->endorsed_by_dean_user }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $registration->endorsed_by_dean ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $registration->endorsed_by_dean ? 'Endorsed' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($registration->status === 'pending')
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Actions</h2>
                <div class="flex space-x-4">
                    <form method="POST" action="{{ route('head-office.registrations.approve', $registration) }}"
                          onsubmit="return validateApprovals(event)">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Approve Registration
                        </button>
                    </form>

                    <form method="POST" action="{{ route('head-office.registrations.reject', $registration) }}" x-data="{ reason: '' }">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="rejection_reason" x-model="reason" placeholder="Rejection reason..." required
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function validateApprovals(event) {
                    const osaPending = {{ $registration->verified_by_osa ? 'false' : 'true' }};
                    const directorPending = {{ $registration->noted_by_director ? 'false' : 'true' }};
                    const vpPending = {{ $registration->approved_by_vp ? 'false' : 'true' }};
                    const deanPending = {{ $registration->endorsed_by_dean ? 'false' : 'true' }};

                    const pendingApprovals = [];

                    if (osaPending) {
                        pendingApprovals.push('• VERIFIED BY Head of Office of Student Affairs & PSG Advisers');
                    }
                    if (directorPending) {
                        pendingApprovals.push('• NOTED BY Director, Student Affairs and Academic Support Services');
                    }
                    if (vpPending) {
                        pendingApprovals.push('• APPROVED BY Vice President for Academics');
                    }
                    if (deanPending) {
                        pendingApprovals.push('• ENDORSED BY Dean');
                    }

                    if (pendingApprovals.length > 0) {
                        event.preventDefault();
                        document.getElementById('pendingApprovalsList').innerHTML = pendingApprovals.join('<br>');
                        document.getElementById('pendingApprovalsModal').classList.remove('hidden');
                        return false;
                    }

                    return true;
                }
            </script>
        @endif

        @if($registration->status === 'rejected' && $registration->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-red-800">Rejection Reason:</h3>
                <p class="text-sm text-red-700 mt-1">{{ $registration->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <!-- Pending Approvals Warning Modal -->
    <div id="pendingApprovalsModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[70]">
        <div class="relative top-1/3 mx-auto p-6 border border-gray-200 w-[480px] bg-white">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">Cannot Approve Registration</h3>
                    <p class="text-sm text-gray-600 mt-2">The following approvals are still pending:</p>
                    <div id="pendingApprovalsList" class="text-sm text-gray-700 mt-3 space-y-1"></div>
                    <p class="text-xs text-gray-500 mt-3">All four approvals must be completed before final approval.</p>
                </div>
            </div>
            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
                <button onclick="document.getElementById('pendingApprovalsModal').classList.add('hidden')" class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">OK</button>
            </div>
        </div>
    </div>
</x-dashboard-layout>






