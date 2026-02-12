<x-dashboard-layout>
    <x-slot name="title">Appeal Applications - Decision Support</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Appeal Applications</h1>
        <p class="text-sm text-gray-500 mt-1">Review and process violation appeals from clubs</p>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <!-- Pending Tab -->
                <a href="{{ route('head-office.decision-support.appeals', ['status' => 'pending']) }}" 
                   class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                          {{ $status === 'pending' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $status === 'pending' ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium border
                                 {{ $status === 'pending' ? 'bg-gray-100 text-gray-900 border-gray-300' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                        {{ $pendingCount }}
                    </span>
                </a>

                <!-- Approved Tab -->
                <a href="{{ route('head-office.decision-support.appeals', ['status' => 'approved']) }}" 
                   class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                          {{ $status === 'approved' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $status === 'approved' ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approved
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium border
                                 {{ $status === 'approved' ? 'bg-gray-100 text-gray-900 border-gray-300' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                        {{ $approvedCount }}
                    </span>
                </a>

                <!-- Rejected Tab -->
                <a href="{{ route('head-office.decision-support.appeals', ['status' => 'rejected']) }}" 
                   class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                          {{ $status === 'rejected' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $status === 'rejected' ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Rejected
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium border
                                 {{ $status === 'rejected' ? 'bg-gray-100 text-gray-900 border-gray-300' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                        {{ $rejectedCount }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="overflow-x-auto">
            @if($appeals->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
                            @if($status === 'pending')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @else
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Decision</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appeals as $appeal)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $appeal->club->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $appeal->club->department }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $appeal->violation->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @php
                                            $severityColors = [
                                                'minor' => 'text-gray-600',
                                                'moderate' => 'text-orange-700',
                                                'major' => 'text-red-700'
                                            ];
                                        @endphp
                                        <span class="text-xs font-medium {{ $severityColors[$appeal->violation->severity] ?? 'text-gray-600' }}">
                                            {{ ucfirst($appeal->violation->severity) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $appeal->submitted_by }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appeal->submitted_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $appeal->submitted_at->format('g:i A') }}</div>
                                </td>
                                @if($status === 'pending')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $confirmedCount = \App\Models\Violation::where('club_id', $appeal->club_id)
                                                ->where('status', 'confirmed')
                                                ->count();
                                        @endphp
                                        @if($appeal->club->status === 'suspended')
                                            <span class="text-xs font-medium text-red-700">Suspended</span>
                                            @if($confirmedCount > 1)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $confirmedCount }} violations remaining
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Last violation
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs font-medium text-gray-600">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button
                                            onclick="showAppealDetails({{ $appeal->id }})"
                                            class="text-gray-900 hover:text-gray-700 font-medium underline"
                                        >
                                            View Details
                                        </button>
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appeal->status === 'approved')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $appeal->updated_at->format('M d, Y') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($status === 'pending')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($status === 'approved')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                    <p class="text-sm text-gray-500">No {{ $status }} appeals</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Appeal Details Modal -->
    <div id="appealModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-gray-200 w-[700px] bg-white mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Appeal Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Review and process violation appeal</p>
                </div>
                <button onclick="closeAppealModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Appeal Content (loaded via AJAX) -->
            <div id="appealContent">
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600">Loading appeal details...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Confirmation Modal -->
    <div id="acceptConfirmModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-1/3 mx-auto p-6 border border-gray-200 w-[480px] bg-white">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">Accept Appeal</h3>
                    <p class="text-sm text-gray-600 mt-2">Are you sure you want to accept this appeal for <span id="acceptClubName" class="font-medium text-gray-900"></span>?</p>
                    <p class="text-xs text-gray-500 mt-2">Suspension will only be lifted if ALL violations have been appealed and resolved.</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button onclick="closeAcceptConfirmModal()" class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">Cancel</button>
                <button id="confirmAcceptBtn" class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">Accept Appeal</button>
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectReasonModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-1/4 mx-auto p-6 border border-gray-200 w-[480px] bg-white">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">Reject Appeal</h3>
                    <p class="text-sm text-gray-600 mt-2">Please provide a reason for rejecting this appeal.</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rejection Reason</label>
                <textarea id="rejectReasonInput" rows="3" class="w-full mt-1 border border-gray-200 p-3 text-sm text-gray-900 focus:outline-none focus:border-gray-400 resize-none" placeholder="Enter reason for rejection..."></textarea>
                <p id="rejectReasonError" class="hidden text-xs text-red-600 mt-1">Please provide a reason for rejection.</p>
            </div>
            <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-200">
                <button onclick="closeRejectReasonModal()" class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">Cancel</button>
                <button id="confirmRejectBtn" class="px-5 py-2 bg-red-600 text-white hover:bg-red-700 transition-colors text-sm font-medium">Reject Appeal</button>
            </div>
        </div>
    </div>

    <!-- Notification Modal (replaces alert) -->
    <div id="notificationModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[70]">
        <div class="relative top-1/3 mx-auto p-6 border border-gray-200 w-[420px] bg-white">
            <div class="flex items-start gap-4">
                <div id="notifIconContainer" class="flex-shrink-0 w-10 h-10 flex items-center justify-center"></div>
                <div class="flex-1">
                    <h3 id="notifTitle" class="text-base font-semibold text-gray-900"></h3>
                    <p id="notifMessage" class="text-sm text-gray-600 mt-2"></p>
                </div>
            </div>
            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
                <button onclick="closeNotificationModal()" class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">OK</button>
            </div>
        </div>
    </div>

    <script>
        // --- Notification Modal ---
        function showNotification(type, title, message, callback) {
            const iconContainer = document.getElementById('notifIconContainer');
            if (type === 'error') {
                iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-red-100 flex items-center justify-center';
                iconContainer.innerHTML = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            } else {
                iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-green-100 flex items-center justify-center';
                iconContainer.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifMessage').textContent = message;
            document.getElementById('notificationModal').classList.remove('hidden');
            window._notifCallback = callback || null;
        }

        function closeNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
            if (window._notifCallback) {
                window._notifCallback();
                window._notifCallback = null;
            }
        }

        // --- Appeal Details Modal ---
        function showAppealDetails(appealId) {
            document.getElementById('appealModal').classList.remove('hidden');
            
            fetch(`/head-office/decision-support/appeal/${appealId}`)
                .then(response => response.json())
                .then(data => {
                    const content = `
                        <!-- Club Information -->
                        <div class="border border-gray-200 p-4 mb-5">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Club Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Club Name</span>
                                    <p class="text-sm font-medium text-gray-900">${data.club_name}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Department</span>
                                    <p class="text-sm font-medium text-gray-900">${data.department}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Violation Information -->
                        <div class="border border-gray-200 p-4 mb-5">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Violation Details</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-xs text-gray-500">Title</span>
                                    <p class="text-sm font-medium text-gray-900">${data.violation_title}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Description</span>
                                    <p class="text-sm text-gray-700">${data.violation_description}</p>
                                </div>
                                <div class="flex gap-4">
                                    <div>
                                        <span class="text-xs text-gray-500">Date</span>
                                        <p class="text-sm text-gray-900">${data.violation_date}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Severity</span>
                                        <p class="text-sm text-gray-900">${data.violation_severity}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appellant Information -->
                        <div class="border border-gray-200 p-4 mb-5">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Appellant Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Name</span>
                                    <p class="text-sm font-medium text-gray-900">${data.submitted_by}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Position</span>
                                    <p class="text-sm font-medium text-gray-900">${data.position || 'N/A'}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Submission Date</span>
                                    <p class="text-sm text-gray-900">${data.submitted_at}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Appeal Description -->
                        <div class="mb-5">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Appeal Description</h4>
                            <div class="border border-gray-200 p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">${data.appeal_reason}</p>
                            </div>
                        </div>

                        <!-- Supporting Documents -->
                        ${data.has_attachment ? `
                            <div class="mb-5">
                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Supporting Documents (${data.attachments.length})</h4>
                                <div class="space-y-2">
                                    ${data.attachments.map((att, i) => `
                                        <div class="border border-gray-200 p-3 flex items-center justify-between">
                                            <div class="flex items-center min-w-0">
                                                <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-900 truncate">${att.name}</span>
                                            </div>
                                            <a href="/head-office/decision-support/appeal/${data.appeal_id}/download/${att.index}" 
                                               class="px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white text-xs transition-colors font-medium flex-shrink-0 ml-3">
                                                Download
                                            </a>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Violations Status Indicator -->
                        ${data.club_status === 'suspended' ? `
                            <div class="mb-5">
                                <div class="border border-gray-200 p-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Club Suspension Status</h4>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Current Status:</span> <span class="font-medium text-red-700">Suspended</span>
                                    </p>
                                    <p class="text-sm text-gray-700 mt-1">
                                        <span class="font-medium">Confirmed Violations:</span> ${data.confirmed_violations_count}
                                    </p>
                                    ${data.confirmed_violations_count > 1 ? `
                                        <p class="text-xs text-gray-500 mt-2">
                                            This club has ${data.confirmed_violations_count} confirmed violation(s). All violations must be appealed and resolved before suspension can be lifted.
                                        </p>
                                    ` : `
                                        <p class="text-xs text-gray-500 mt-2">
                                            This is the last confirmed violation. Accepting this appeal will lift the suspension.
                                        </p>
                                    `}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                onclick="closeAppealModal()"
                                class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"
                            >
                                Cancel
                            </button>
                            <button
                                onclick="rejectAppeal(${data.appeal_id})"
                                class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium"
                            >
                                Reject Appeal
                            </button>
                            <button
                                onclick="acceptAppeal(${data.appeal_id}, '${data.club_name}')"
                                class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium"
                            >
                                Accept Appeal
                            </button>
                        </div>
                    `;
                    
                    document.getElementById('appealContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('appealContent').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">Error loading appeal details. Please try again.</p>
                        </div>
                    `;
                });
        }

        function closeAppealModal() {
            document.getElementById('appealModal').classList.add('hidden');
        }

        function acceptAppeal(appealId, clubName) {
            document.getElementById('acceptClubName').textContent = clubName;
            document.getElementById('acceptConfirmModal').classList.remove('hidden');
            
            document.getElementById('confirmAcceptBtn').onclick = function() {
                this.disabled = true;
                this.textContent = 'Processing...';
                document.getElementById('acceptConfirmModal').classList.add('hidden');
                
                fetch(`/head-office/decision-support/appeal/${appealId}/accept`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAppealModal();
                        showNotification('success', 'Appeal Accepted', 'The appeal has been accepted successfully.', () => {
                            window.location.reload();
                        });
                    } else {
                        showNotification('error', 'Error', data.error || data.message || 'Error accepting appeal.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error', 'An error occurred while accepting the appeal.');
                });
            };
        }

        function closeAcceptConfirmModal() {
            document.getElementById('acceptConfirmModal').classList.add('hidden');
        }

        function rejectAppeal(appealId) {
            document.getElementById('rejectReasonInput').value = '';
            document.getElementById('rejectReasonError').classList.add('hidden');
            document.getElementById('rejectReasonModal').classList.remove('hidden');
            
            document.getElementById('confirmRejectBtn').onclick = function() {
                const reason = document.getElementById('rejectReasonInput').value.trim();
                if (!reason) {
                    document.getElementById('rejectReasonError').classList.remove('hidden');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Processing...';
                document.getElementById('rejectReasonModal').classList.add('hidden');

                fetch(`/head-office/decision-support/appeal/${appealId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAppealModal();
                        showNotification('success', 'Appeal Rejected', 'The appeal has been rejected successfully.', () => {
                            window.location.reload();
                        });
                    } else {
                        showNotification('error', 'Error', data.message || 'Error rejecting appeal.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error', 'An error occurred while rejecting the appeal.');
                });
            };
        }

        function closeRejectReasonModal() {
            document.getElementById('rejectReasonModal').classList.add('hidden');
        }
    </script>
</x-dashboard-layout>
