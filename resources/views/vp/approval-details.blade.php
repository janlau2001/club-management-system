<x-dashboard-layout>
    <x-slot name="title">Application Details - {{ $registration->club_name }}</x-slot>

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $registration->club_name }}</h1>
                <p class="text-gray-600 mt-2">Final VP Approval for Club Registration</p>
            </div>
            <a href="{{ route('vp.approvals') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                ← Back to Approvals
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Club Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Club Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Rationale</label>
                        @php
                            $rationaleLength = strlen($registration->rationale);
                            $isLongRationale = $rationaleLength > 300;
                        @endphp
                        @if($isLongRationale)
                            <div class="mt-1">
                                <p id="rationale-short" class="text-sm text-gray-900 break-words overflow-wrap-anywhere">
                                    {{ Str::limit($registration->rationale, 300) }}
                                </p>
                                <p id="rationale-full" class="text-sm text-gray-900 break-words overflow-wrap-anywhere hidden">
                                    {{ $registration->rationale }}
                                </p>
                                <button onclick="toggleRationale()" id="rationale-toggle" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 focus:outline-none">
                                    Read More
                                </button>
                            </div>
                            <script>
                                function toggleRationale() {
                                    const shortText = document.getElementById('rationale-short');
                                    const fullText = document.getElementById('rationale-full');
                                    const toggleBtn = document.getElementById('rationale-toggle');
                                    
                                    if (shortText.classList.contains('hidden')) {
                                        shortText.classList.remove('hidden');
                                        fullText.classList.add('hidden');
                                        toggleBtn.textContent = 'Read More';
                                    } else {
                                        shortText.classList.add('hidden');
                                        fullText.classList.remove('hidden');
                                        toggleBtn.textContent = 'Read Less';
                                    }
                                }
                            </script>
                        @else
                            <p class="mt-1 text-sm text-gray-900 break-words overflow-wrap-anywhere">{{ $registration->rationale }}</p>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Submitted At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->submitted_at ? $registration->submitted_at->format('F d, Y g:i A') : 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($registration->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submitted Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Submitted Documents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Constitution and By-Laws</label>
                        @if($registration->constitution_file)
                            <a href="{{ route('vp.approvals.document', ['registration' => $registration, 'type' => 'constitution']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">List of Officers</label>
                        @if($registration->officers_list_file)
                            <a href="{{ route('vp.approvals.document', ['registration' => $registration, 'type' => 'officers_list']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activities/Action Plan</label>
                        @if($registration->activities_plan_file)
                            <a href="{{ route('vp.approvals.document', ['registration' => $registration, 'type' => 'activities_plan']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Budget Proposal</label>
                        @if($registration->budget_proposal_file)
                            <a href="{{ route('vp.approvals.document', ['registration' => $registration, 'type' => 'budget_proposal']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Officer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Officer Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->officer->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->officer->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->officer->student_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->officer->department }}</p>
                    </div>
                </div>
            </div>

            <!-- Sequential Approval Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Sequential Approval Status</h3>
                <div class="space-y-4">
                    <!-- Current Status Badge -->
                    @if($registration->approved_by_vp)
                        <div class="flex items-center p-4 bg-green-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Status: FULLY APPROVED & REGISTERED</p>
                                <p class="text-sm text-green-700">Club is now officially registered and active</p>
                            </div>
                        </div>
                    @elseif(!$registration->endorsed_by_dean)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Status: AWAITING DEAN ENDORSEMENT</p>
                                <p class="text-sm text-gray-700">Step 1 of 4 - Dean must endorse this application first</p>
                            </div>
                        </div>
                    @elseif(!$registration->approved_by_psg_council)
                        <div class="flex items-center p-4 bg-yellow-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-900">Status: AWAITING PSG COUNCIL APPROVAL</p>
                                <p class="text-sm text-yellow-700">Step 2 of 4 - PSG Council Adviser must approve this application</p>
                            </div>
                        </div>
                    @elseif(!$registration->noted_by_director)
                        <div class="flex items-center p-4 bg-orange-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-orange-900">Status: AWAITING DIRECTOR NOTING</p>
                                <p class="text-sm text-orange-700">Step 3 of 4 - Director of Student Affairs must note this application</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-8a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-900">Status: READY FOR FINAL VP APPROVAL</p>
                                <p class="text-sm text-blue-700">Final step - awaiting VP approval to officially register the club</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Step 1: Dean Endorsement -->
                    <div class="flex items-center">
                        @if($registration->endorsed_by_dean)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Step 1: ✓ ENDORSED BY Dean</p>
                                <p class="text-sm text-green-700">Endorsed on {{ $registration->endorsed_by_dean_at->format('M d, Y g:i A') }} by {{ $registration->endorsed_by_dean_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Step 1: PENDING Dean Endorsement</p>
                                <p class="text-sm text-gray-500">Awaiting Dean to endorse this application</p>
                            </div>
                        @endif
                    </div>

                    <!-- Step 2: PSG Council Approval -->
                    <div class="flex items-center">
                        @if($registration->approved_by_psg_council)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Step 2: ✓ APPROVED BY PSG Council Adviser</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_psg_council_at->format('M d, Y g:i A') }} by {{ $registration->approved_by_psg_council_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Step 2: PENDING PSG Council Approval</p>
                                <p class="text-sm text-gray-500">Awaiting PSG Council Adviser to approve</p>
                            </div>
                        @endif
                    </div>

                    <!-- Step 3: Director Approval -->
                    <div class="flex items-center">
                        @if($registration->noted_by_director)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Step 3: ✓ NOTED BY Director</p>
                                <p class="text-sm text-green-700">Noted on {{ $registration->noted_by_director_at->format('M d, Y g:i A') }} by {{ $registration->noted_by_director_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Step 3: PENDING Director Noting</p>
                                <p class="text-sm text-gray-500">Awaiting Director to note this application</p>
                            </div>
                        @endif
                    </div>

                    <!-- Step 4: VP Approval (Final Step) -->
                    <div class="flex items-center">
                        @if($registration->approved_by_vp)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-900">Step 4: ✓ APPROVED BY Vice President for Academics</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_vp_at ? $registration->approved_by_vp_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->approved_by_vp_user ?? 'VP Academics' }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-900">Step 4: FINAL APPROVAL BY Vice President for Academics</p>
                                <p class="text-sm text-blue-700">Ready for your approval - This will officially register the club</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if(!$registration->approved_by_vp && $registration->status !== 'rejected')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Final VP Actions</h3>
                    <div class="space-y-3">
                        <button type="button"
                                class="w-full bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="openApproveModal('{{ $registration->club_name }}', '{{ route('vp.approvals.approve', $registration) }}')">
                            Approve & Register Club
                        </button>
                        <button type="button"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="openRejectModal('{{ $registration->club_name }}', '{{ route('vp.approvals.reject', $registration) }}')">
                            Reject Registration
                        </button>
                    </div>
                </div>
            @elseif($registration->approved_by_vp)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900">Club Successfully Registered!</h3>
                    </div>
                    <p class="text-green-800 mb-2">You have successfully approved this registration and the club is now officially registered.</p>
                    <p class="text-sm text-green-600">Approved on {{ $registration->approved_by_vp_at?->format('F j, Y \a\t g:i A') ?? 'N/A' }}</p>
                    <p class="text-sm text-green-600">Approved by: {{ $registration->approved_by_vp_user ?? 'VP Academics' }}</p>
                    <div class="mt-4 p-3 bg-green-100 rounded-lg">
                        <p class="text-xs text-green-700 font-medium">✓ Club is now active and visible in organization dashboards</p>
                        <p class="text-xs text-green-600 mt-1">✓ Available for all administrators to view and manage</p>
                    </div>
                </div>
            @endif

            @if($registration->status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-red-900">Registration Rejected</h3>
                    </div>
                    <p class="text-red-800 mb-2"><strong>Reason:</strong></p>
                    <p class="text-red-700 bg-red-100 p-3 rounded-lg">{{ $registration->rejection_reason }}</p>
                    <p class="text-sm text-red-600 mt-3">Rejected on {{ $registration->rejected_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Include the approval and rejection modals from the approvals page -->
    @include('vp.partials.approval-modals')

    <script>
        function openApproveModal(clubName, url) {
            document.getElementById('approveMessage').textContent = 'Are you sure you want to approve and register "' + clubName + '"?';
            document.getElementById('approveForm').action = url;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(clubName, url) {
            document.getElementById('rejectMessage').textContent = 'Are you sure you want to reject the registration for "' + clubName + '"?';
            document.getElementById('rejectForm').action = url;
            document.getElementById('rejectModal').classList.remove('hidden');
            
            // Clear the textarea
            document.getElementById('rejection_reason').value = '';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Handle form submissions with AJAX
        document.getElementById('approveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeApproveModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRejectModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            
            if (event.target === approveModal) {
                closeApproveModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        }
    </script>
</x-dashboard-layout>
