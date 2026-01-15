<x-dashboard-layout>
    <x-slot name="title">VP Academics Renewal Details</x-slot>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $renewal->club->name }}</h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                        Academic Year {{ $renewal->academic_year }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Submitted {{ $renewal->submitted_at->format('M j, Y') }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $renewal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                       ($renewal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $renewal->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Club Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                        </svg>
                        Club Information
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Department</label>
                            <p class="text-gray-900 font-medium">{{ $renewal->department }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nature</label>
                            <p class="text-gray-900 font-medium">{{ ucfirst($renewal->nature) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Faculty Adviser</label>
                            <p class="text-gray-900 font-medium">{{ $renewal->faculty_adviser }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Renewal Date</label>
                            <p class="text-gray-900 font-medium">
                                {{ $renewal->last_renewal_date ? $renewal->last_renewal_date->format('M j, Y') : 'Not set' }}
                            </p>
                        </div>
                    </div>
                    @if($renewal->rationale)
                        <div>
                            <label class=\"text-sm font-medium text-gray-500\">Rationale for Renewal</label>\n                            @php\n                                $rationaleLength = strlen($renewal->rationale);\n                                $isLongRationale = $rationaleLength > 300;\n                            @endphp\n                            @if($isLongRationale)\n                                <div class=\"mt-1 p-3 bg-gray-50 rounded-lg\">\n                                    <p id=\"renewal-rationale-short\" class=\"text-gray-900 text-sm leading-relaxed break-words overflow-wrap-anywhere\">\n                                        {{ Str::limit($renewal->rationale, 300) }}\n                                    </p>\n                                    <p id=\"renewal-rationale-full\" class=\"text-gray-900 text-sm leading-relaxed break-words overflow-wrap-anywhere hidden\">\n                                        {{ $renewal->rationale }}\n                                    </p>\n                                    <button onclick=\"toggleRenewalRationale()\" id=\"renewal-rationale-toggle\" class=\"text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 focus:outline-none\">\n                                        Read More\n                                    </button>\n                                </div>\n                                <script>\n                                    function toggleRenewalRationale() {\n                                        const shortText = document.getElementById('renewal-rationale-short');\n                                        const fullText = document.getElementById('renewal-rationale-full');\n                                        const toggleBtn = document.getElementById('renewal-rationale-toggle');\n                                        \n                                        if (shortText.classList.contains('hidden')) {\n                                            shortText.classList.remove('hidden');\n                                            fullText.classList.add('hidden');\n                                            toggleBtn.textContent = 'Read More';\n                                        } else {\n                                            shortText.classList.add('hidden');\n                                            fullText.classList.remove('hidden');\n                                            toggleBtn.textContent = 'Read Less';\n                                        }\n                                    }\n                                </script>\n                            @else\n                                <div class=\"mt-1 p-3 bg-gray-50 rounded-lg\">\n                                    <p class=\"text-gray-900 text-sm leading-relaxed break-words overflow-wrap-anywhere\">{{ $renewal->rationale }}</p>\n                                </div>\n                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                        </svg>
                        Submitted Documents
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $documents = [
                                ['file' => $renewal->officers_list_file, 'label' => 'Officers List', 'icon' => 'users'],
                                ['file' => $renewal->activities_plan_file, 'label' => 'Activities Plan', 'icon' => 'calendar'],
                                ['file' => $renewal->budget_proposal_file, 'label' => 'Budget Proposal', 'icon' => 'currency-dollar'],
                                ['file' => $renewal->constitution_file, 'label' => 'Constitution', 'icon' => 'document-text']
                            ];
                        @endphp
                        
                        @foreach($documents as $doc)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg
                                {{ $doc['file'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                                    {{ $doc['file'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($doc['icon'] === 'users')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                        </svg>
                                    @elseif($doc['icon'] === 'calendar')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($doc['icon'] === 'currency-dollar')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium {{ $doc['file'] ? 'text-green-900' : 'text-red-900' }}">
                                        {{ $doc['label'] }}
                                    </p>
                                    <p class="text-xs {{ $doc['file'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $doc['file'] ? $doc['file'] : 'Not submitted' }}
                                    </p>
                                </div>
                                @if($doc['file'])
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Approval Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Approval Progress
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- President -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                                {{ $renewal->prepared_by_president ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                @if($renewal->prepared_by_president)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-medium">1</span>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">President Preparation</p>
                                <p class="text-xs text-gray-500">
                                    {{ $renewal->prepared_by_president ? 'Completed' : 'Pending' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Adviser -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                                {{ $renewal->certified_by_adviser ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                @if($renewal->certified_by_adviser)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-medium">2</span>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Adviser Certification</p>
                                <p class="text-xs text-gray-500">
                                    {{ $renewal->certified_by_adviser ? 'Completed' : 'Pending' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- PSG Council -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                                {{ $renewal->reviewed_by_psg ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                @if($renewal->reviewed_by_psg)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-medium">3</span>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">PSG Council Review</p>
                                <p class="text-xs text-gray-500">
                                    {{ $renewal->reviewed_by_psg ? 'Completed' : 'Pending' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- VP Academics -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                                {{ $renewal->approved_by_vp ? 'bg-green-100 text-green-600' : 'bg-indigo-100 text-indigo-600' }}">
                                @if($renewal->approved_by_vp)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-medium">4</span>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">VP Academics Approval</p>
                                <p class="text-xs {{ $renewal->approved_by_vp ? 'text-green-600' : 'text-indigo-600' }}">
                                    {{ $renewal->approved_by_vp ? 'Completed' : 'Current Step' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Actions Required</h3>
                </div>
                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="text-red-800 text-sm">
                                <strong>Error:</strong>
                                <ul class="mt-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(!$renewal->approved_by_vp)
                        <div class="space-y-4">
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <p class="text-sm text-indigo-800">
                                    <strong>VP Academics Approval:</strong>
                                    By approving this renewal, you confirm that the club meets all academic standards and requirements for the upcoming academic year.
                                </p>
                            </div>

                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('vp.renewal-approvals.approve', $renewal) }}" 
                                  x-data="{ showApprove: false }" class="space-y-3">
                                @csrf
                                <button type="button" @click="showApprove = true" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg 
                                               hover:bg-green-700 transition-colors duration-200 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approve Renewal
                                </button>
                                
                                <div x-show="showApprove" x-transition class="space-y-3 p-4 bg-gray-50 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700">Enter your password to confirm</label>
                                    <input name="current_password" type="password" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           placeholder="Enter your password">
                                    <div class="flex space-x-2">
                                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Confirm Approval
                                        </button>
                                        <button type="button" @click="showApprove = false" 
                                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Reject Form -->
                            <form method="POST" action="{{ route('vp.renewal-approvals.reject', $renewal) }}" 
                                  x-data="{ showReject: false }" class="space-y-3">
                                @csrf
                                <button type="button" @click="showReject = true" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg 
                                               hover:bg-red-700 transition-colors duration-200 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Reject Renewal
                                </button>
                                
                                <div x-show="showReject" x-transition class="space-y-3 p-4 bg-gray-50 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700">Enter your password to confirm</label>
                                    <input name="current_password" type="password" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                                           placeholder="Enter your password">
                                    <label class="block text-sm font-medium text-gray-700">Reason for rejection</label>
                                    <textarea name="rejection_reason" required rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                                              placeholder="Explain why this renewal is being rejected..."></textarea>
                                    <div class="flex space-x-2">
                                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            Confirm Rejection
                                        </button>
                                        <button type="button" @click="showReject = false" 
                                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Already Approved</h3>
                                    <p class="text-sm text-green-700">
                                        Approved by {{ $renewal->approved_by_vp_user }} on {{ $renewal->approved_by_vp_at->format('M j, Y g:i A') }}
                                    </p>
                                    <p class="text-xs text-green-600 mt-1">→ This renewal will continue in the parallel approval system.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <a href="{{ route('vp.renewal-approvals') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg 
                              hover:bg-gray-700 transition-colors duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Back to Renewal Approvals
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>




