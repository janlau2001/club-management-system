<x-dashboard-layout>
    <x-slot name="title">Application Details - {{ $registration->club_name }}</x-slot>

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $registration->club_name }}</h1>
                <p class="text-gray-600 mt-2">Application for Recognition</p>
            </div>
            <a href="{{ route('head-office.approvals') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                ← Back to Registration Monitoring
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
                            <a href="{{ route('head-office.approvals.document', ['registration' => $registration, 'type' => 'constitution']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">List of Officers</label>
                        @if($registration->officers_list_file)
                            <a href="{{ route('head-office.approvals.document', ['registration' => $registration, 'type' => 'officers']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activities/Action Plan</label>
                        @if($registration->activities_plan_file)
                            <a href="{{ route('head-office.approvals.document', ['registration' => $registration, 'type' => 'activities']) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800" target="_blank">View Document</a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Budget Proposal</label>
                        @if($registration->budget_proposal_file)
                            <a href="{{ route('head-office.approvals.document', ['registration' => $registration, 'type' => 'budget']) }}" 
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

            <!-- Approval Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Status</h3>
                <div class="space-y-4">
                    <!-- Dean Endorsement -->
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
                                <p class="text-sm font-medium text-green-900">ENDORSED BY Dean</p>
                                <p class="text-sm text-green-700">Endorsed on {{ $registration->endorsed_by_dean_at ? $registration->endorsed_by_dean_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->endorsed_by_dean_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $registration->current_approval_step === 'dean' ? 'yellow' : 'gray' }}-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-{{ $registration->current_approval_step === 'dean' ? 'yellow' : 'gray' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-{{ $registration->current_approval_step === 'dean' ? 'yellow' : 'gray' }}-900">ENDORSED BY Dean</p>
                                <p class="text-sm text-{{ $registration->current_approval_step === 'dean' ? 'yellow' : 'gray' }}-700">{{ $registration->current_approval_step === 'dean' ? 'Current Step' : 'Pending' }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- PSG Council Approval -->
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
                                <p class="text-sm font-medium text-green-900">APPROVED BY PSG Council</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_psg_council_at ? $registration->approved_by_psg_council_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->approved_by_psg_council_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $registration->current_approval_step === 'psg_council' ? 'yellow' : 'gray' }}-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-{{ $registration->current_approval_step === 'psg_council' ? 'yellow' : 'gray' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-{{ $registration->current_approval_step === 'psg_council' ? 'yellow' : 'gray' }}-900">APPROVED BY PSG Council</p>
                                <p class="text-sm text-{{ $registration->current_approval_step === 'psg_council' ? 'yellow' : 'gray' }}-700">{{ $registration->current_approval_step === 'psg_council' ? 'Current Step' : 'Pending' }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Director Approval -->
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
                                <p class="text-sm font-medium text-green-900">NOTED BY Director, Student Affairs and Academic Support Services</p>
                                <p class="text-sm text-green-700">Noted on {{ $registration->noted_by_director_at ? $registration->noted_by_director_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->noted_by_director_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $registration->current_approval_step === 'director' ? 'yellow' : 'gray' }}-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-{{ $registration->current_approval_step === 'director' ? 'yellow' : 'gray' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-{{ $registration->current_approval_step === 'director' ? 'yellow' : 'gray' }}-900">NOTED BY Director, Student Affairs and Academic Support Services</p>
                                <p class="text-sm text-{{ $registration->current_approval_step === 'director' ? 'yellow' : 'gray' }}-700">{{ $registration->current_approval_step === 'director' ? 'Current Step' : 'Pending' }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- VP Approval -->
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
                                <p class="text-sm font-medium text-green-900">APPROVED BY Vice President for Academics</p>
                                <p class="text-sm text-green-700">Approved on {{ $registration->approved_by_vp_at ? $registration->approved_by_vp_at->format('M d, Y g:i A') : 'N/A' }} by {{ $registration->approved_by_vp_user }}</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $registration->current_approval_step === 'vp' ? 'yellow' : 'gray' }}-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-{{ $registration->current_approval_step === 'vp' ? 'yellow' : 'gray' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-{{ $registration->current_approval_step === 'vp' ? 'yellow' : 'gray' }}-900">APPROVED BY Vice President for Academics</p>
                                <p class="text-sm text-{{ $registration->current_approval_step === 'vp' ? 'yellow' : 'gray' }}-700">{{ $registration->current_approval_step === 'vp' ? 'Current Step' : 'Pending' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Registration Status -->
            @if($registration->status === 'approved')
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900">Registration Fully Approved</h3>
                    </div>
                    <p class="text-green-800 mb-2">This registration has completed all approval steps and the club is now active.</p>
                    <p class="text-sm text-green-600">Completed on {{ $registration->approved_at?->format('F j, Y \a\t g:i A') ?? 'N/A' }}</p>
                </div>
            @elseif($registration->status === 'pending')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-900">Registration In Progress</h3>
                    </div>
                    <p class="text-blue-800 mb-2">This registration is currently being processed through the approval workflow.</p>
                    <div class="text-sm text-blue-600">
                        <strong>Current Step:</strong>
                        @if($registration->approved_by_vp)
                            Registration Complete - All approvals received
                        @elseif($registration->noted_by_director)
                            Awaiting VP approval
                        @elseif($registration->approved_by_psg_council)
                            Awaiting Director noting
                        @elseif($registration->endorsed_by_dean)
                            Awaiting PSG Council approval
                        @else
                            Awaiting Dean endorsement
                        @endif
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
</x-dashboard-layout>
