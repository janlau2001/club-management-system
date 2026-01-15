<x-dashboard-layout>
    <x-slot name="title">Application Details - {{ $registration->club_name }}</x-slot>

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $registration->club_name }}</h1>
                <p class="text-gray-600 mt-2">Application for Recognition</p>
            </div>
            <a href="{{ route('psg-council.approvals') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
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
                            <a href="{{ route('psg-council.approvals.document', ['registration' => $registration, 'type' => 'constitution']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">List of Officers</label>
                        @if($registration->officers_list_file)
                            <a href="{{ route('psg-council.approvals.document', ['registration' => $registration, 'type' => 'officers']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activities/Action Plan</label>
                        @if($registration->activities_plan_file)
                            <a href="{{ route('psg-council.approvals.document', ['registration' => $registration, 'type' => 'activities']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Budget Proposal</label>
                        @if($registration->budget_proposal_file)
                            <a href="{{ route('psg-council.approvals.document', ['registration' => $registration, 'type' => 'budget']) }}" 
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
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-8a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            @if($registration->approved_by_vp)
                                <p class="text-sm font-medium text-green-900">Current Status: Fully Approved</p>
                                <p class="text-sm text-green-700">All approvals completed</p>
                            @elseif($registration->noted_by_director)
                                <p class="text-sm font-medium text-blue-900">Current Status: Waiting for VP Approval</p>
                                <p class="text-sm text-blue-700">Pending approval from Vice President for Academics</p>
                            @elseif($registration->approved_by_psg_council)
                                <p class="text-sm font-medium text-blue-900">Current Status: Waiting for Director Noting</p>
                                <p class="text-sm text-blue-700">Pending approval from Director</p>
                            @elseif($registration->endorsed_by_dean)
                                <p class="text-sm font-medium text-blue-900">Current Status: Waiting for PSG Council Approval</p>
                                <p class="text-sm text-blue-700">Pending approval from PSG Council Adviser</p>
                            @else
                                <p class="text-sm font-medium text-blue-900">Current Status: Waiting for Dean Endorsement</p>
                                <p class="text-sm text-blue-700">Pending approval from Dean</p>
                            @endif
                        </div>
                    </div>
                    
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
                                <p class="text-sm font-medium text-green-900">Step 1: ENDORSED BY Dean</p>
                                <p class="text-sm text-green-700">Endorsed on {{ $registration->endorsed_by_dean_at ? $registration->endorsed_by_dean_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->endorsed_by_dean_user ?? 'Dean' }}</p>
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
                                <p class="text-sm font-medium text-gray-900">Step 1: ENDORSED BY Dean</p>
                                <p class="text-sm text-gray-500">Pending</p>
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
                                <p class="text-sm font-medium text-green-900">Step 2: APPROVED BY PSG Council Adviser</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_psg_council_at ? $registration->approved_by_psg_council_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->approved_by_psg_council_user ?? 'PSG Council' }}</p>
                            </div>
                        @elseif($registration->endorsed_by_dean)
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-900">Step 2: APPROVED BY PSG Council Adviser</p>
                                <p class="text-sm text-yellow-700">Pending</p>
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
                                <p class="text-sm font-medium text-gray-900">Step 2: APPROVED BY PSG Council Adviser</p>
                                <p class="text-sm text-gray-500">Pending</p>
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
                                <p class="text-sm font-medium text-green-900">Step 3: NOTED BY Director</p>
                                <p class="text-sm text-green-700">Noted on {{ $registration->noted_by_director_at ? $registration->noted_by_director_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->noted_by_director_user ?? 'Director' }}</p>
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
                                <p class="text-sm font-medium text-gray-900">Step 3: NOTED BY Director</p>
                                <p class="text-sm text-gray-500">Pending</p>
                            </div>
                        @endif
                    </div>

                    <!-- Step 4: VP Approval -->
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
                                <p class="text-sm font-medium text-green-900">Step 4: APPROVED BY Vice President for Academics</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_vp_at ? $registration->approved_by_vp_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->approved_by_vp_user ?? 'VP Academics' }}</p>
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
                                <p class="text-sm font-medium text-gray-900">Step 4: APPROVED BY Vice President for Academics</p>
                                <p class="text-sm text-gray-500">Pending</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($registration->endorsed_by_dean && !$registration->approved_by_psg_council && $registration->status !== 'rejected')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="button"
                                class="w-full bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="openApproveModal('{{ $registration->club_name }}', '{{ route('psg-council.approvals.approve', $registration) }}')">
                            Approve Registration
                        </button>
                        <button type="button"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium"
                                onclick="openRejectModal('{{ $registration->club_name }}', '{{ route('psg-council.approvals.reject', $registration) }}')">
                            Reject Registration
                        </button>
                    </div>
                </div>
            @elseif($registration->approved_by_psg_council)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900">Already Approved by PSG Council</h3>
                    </div>
                    <p class="text-green-800 mb-2">You have already approved this registration.</p>
                    <p class="text-sm text-green-600">Approved on {{ $registration->approved_by_psg_council_at?->format('F j, Y \a\t g:i A') ?? 'N/A' }}</p>
                    <p class="text-sm text-green-600">Approved by: {{ $registration->approved_by_psg_council_user ?? 'PSG Council Adviser' }}</p>
                </div>
            @endif

            @if(!$registration->endorsed_by_dean)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-yellow-900">Waiting for Dean Endorsement</h3>
                    </div>
                    <p class="text-yellow-800">This application needs to be endorsed by the Dean before PSG Council can review it.</p>
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
                    <p class="text-red-700 bg-red-100 p-3 rounded-lg">{{ $registration->rejection_reason ?? 'No reason provided' }}</p>
                    <p class="text-sm text-red-600 mt-3">Rejected on {{ $registration->rejected_at?->format('F j, Y \a\t g:i A') ?? 'N/A' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Registration Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Approve Registration</h3>
                <div class="mt-4 mb-6">
                    <p class="text-sm text-gray-500 mb-4" id="approveMessage">Are you sure you want to approve this registration?</p>
                    <p class="text-xs text-red-700 mb-2">This is a sensitive action. Please enter your password to confirm.</p>
                    <div class="flex items-center text-xs text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Verifying as: <strong>PSG Council Adviser</strong></span>
                    </div>
                </div>

                <div class="text-left">
                    <form id="approveForm" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                            <input type="password" name="current_password" required placeholder="Enter your password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeApproveModal()" 
                                    class="flex-1 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-md hover:bg-green-800">
                                Approve Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Reject Registration</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="rejectMessage">Are you sure you want to reject this registration?</p>
                </div>
                <div class="text-left">
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                      placeholder="Please provide a reason for rejecting this application..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                            <input type="password" name="current_password" required placeholder="Enter your password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeRejectModal()" 
                                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Reject Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(clubName, url) {
            document.getElementById('approveMessage').textContent = 'Are you sure you want to approve the registration for "' + clubName + '"?';
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
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

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
