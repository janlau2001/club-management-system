<x-dashboard-layout>
    <x-slot name="title">Club Renewals</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Club Renewals</h1>
            <p class="text-gray-600 mt-1">Manage organization renewal applications and track renewal status</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded font-medium flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export Report</span>
            </button>
            <button onclick="sendBulkReminders()" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded font-medium flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Send Reminders</span>
            </button>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="bg-white border-2 border-gray-900 rounded p-2.5 mr-3">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Upcoming Renewals</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-0.5">{{ $upcomingRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Due in 30 days</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="bg-white border-2 border-gray-900 rounded p-2.5 mr-3">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Due Now</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-0.5">{{ $dueRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Due for renewal</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="bg-white border-2 border-gray-900 rounded p-2.5 mr-3">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Overdue</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-0.5">{{ $overdueRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Past due date</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center">
                <div class="bg-white border-2 border-gray-900 rounded p-2.5 mr-3">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Submitted This Year</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-0.5">{{ $submittedRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Renewal requests</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('head-office.renewals') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search organizations..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Renewal Status</label>
                    <select name="renewal_status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Clubs</option>
                        <option value="upcoming" {{ request('renewal_status') == 'upcoming' ? 'selected' : '' }}>Upcoming (Due in 30 days)</option>
                        <option value="due" {{ request('renewal_status') == 'due' ? 'selected' : '' }}>Due Now</option>
                        <option value="overdue" {{ request('renewal_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="recent" {{ request('renewal_status') == 'recent' ? 'selected' : '' }}>Recently Renewed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Department</label>
                    <select name="department" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Departments</option>
                        <option value="SASTE" {{ request('department') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                        <option value="SNAHS" {{ request('department') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                        <option value="SITE" {{ request('department') == 'SITE' ? 'selected' : '' }}>SITE</option>
                        <option value="SBAHM" {{ request('department') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                        <option value="BEU" {{ request('department') == 'BEU' ? 'selected' : '' }}>BEU</option>
                        <option value="SOM" {{ request('department') == 'SOM' ? 'selected' : '' }}>SOM</option>
                        <option value="GRADUATE SCHOOL" {{ request('department') == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded font-medium transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Renewals List -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="p-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Club Renewal Status</h3>
                <div class="text-sm text-gray-600">
                    {{ $renewalData->count() ?? 0 }} clubs
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Date Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Date of Last Renewal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Days Until Due</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Renewal Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Submission Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($renewalData ?? [] as $item)
                        @php
                            $departmentColors = [
                                'SASTE' => 'bg-blue-100 text-blue-600',
                                'SBAHM' => 'bg-green-100 text-green-600',
                                'SNAHS' => 'bg-purple-100 text-purple-600',
                                'SITE' => 'bg-red-100 text-red-600',
                                'BEU' => 'bg-yellow-100 text-yellow-600',
                                'SOM' => 'bg-indigo-100 text-indigo-600',
                                'GRADUATE SCHOOL' => 'bg-pink-100 text-pink-600'
                            ];
                            $colorClass = $departmentColors[$item->department] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="{{ $colorClass }} p-2 rounded mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->club_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->department }} • {{ $item->member_count }} members</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->date_registered ? $item->date_registered->format('M j, Y') : 'Not set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->last_renewal_date ? $item->last_renewal_date->format('M j, Y') : 'Never renewed' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($item->days_until_due > 0)
                                    <span class="text-blue-600 font-medium">Due in {{ (int)$item->days_until_due }} days</span>
                                @elseif($item->days_until_due === 0)
                                    <span class="text-orange-600 font-medium">Due Today</span>
                                @else
                                    <span class="text-red-600 font-medium">Overdue by {{ (int)abs($item->days_until_due) }} days</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-medium rounded {{ $item->status_badge }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->has_submitted)
                                    <span class="px-2.5 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                                        {{ $item->submission_status }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded bg-gray-100 text-gray-600">
                                        Not Submitted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                <a href="{{ route('head-office.organization.show', $item->club) }}" class="text-blue-600 hover:text-blue-800">View Club</a>
                                @if($item->has_submitted)
                                    <a href="{{ route('head-office.renewals.show', $item->submitted_renewal->id) }}" class="text-green-600 hover:text-green-800">View Renewal</a>
                                @endif
                                @if(in_array($item->renewal_status, ['upcoming', 'due', 'overdue']) && !$item->has_submitted)
                                    <button class="text-orange-600 hover:text-orange-800" onclick="sendRenewalReminder({{ $item->club->id }})">Send Reminder</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No clubs found</h3>
                                    <p class="text-gray-500">There are no clubs matching your current filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-1/3 mx-auto p-6 border border-gray-200 w-[480px] bg-white">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 id="confirmTitle" class="text-base font-semibold text-gray-900"></h3>
                    <p id="confirmMessage" class="text-sm text-gray-600 mt-2"></p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button onclick="closeConfirmModal()" class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">Cancel</button>
                <button id="confirmActionBtn" class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
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

    <!-- JavaScript for reminder functionality -->
    <script>
    // --- Modal Helpers ---
    function showNotification(type, title, message, callback) {
        const iconContainer = document.getElementById('notifIconContainer');
        if (type === 'error') {
            iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-red-100 flex items-center justify-center';
            iconContainer.innerHTML = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
        } else if (type === 'warning') {
            iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-amber-100 flex items-center justify-center';
            iconContainer.innerHTML = '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
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

    function showConfirm(title, message, onConfirm) {
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmActionBtn').onclick = function() {
            closeConfirmModal();
            onConfirm();
        };
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function sendRenewalReminder(clubId) {
        showConfirm('Send Reminder', 'Send renewal reminder to all members of this club?', function() {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Sending...';
            button.disabled = true;

            fetch('{{ route('head-office.renewals.send-reminder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    club_id: clubId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Reminder Sent', data.message);
                    button.textContent = 'Sent ✓';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 5000);
                } else {
                    showNotification('error', 'Failed', 'Failed to send reminder. Please try again.');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', 'An error occurred while sending the reminder.');
                button.textContent = originalText;
                button.disabled = false;
            });
        });
    }

    function sendBulkReminders() {
        showConfirm('Send Bulk Reminders', 'Send renewal reminders to all overdue clubs? This will notify all members of overdue clubs.', function() {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Sending...';
            button.disabled = true;

            const overdueClubs = [];
            document.querySelectorAll('tr').forEach(row => {
                const statusText = row.textContent;
                if (statusText.includes('Overdue by') && !statusText.includes('Not Submitted')) {
                    const clubId = row.querySelector('button[onclick*="sendRenewalReminder"]')?.getAttribute('onclick')?.match(/\d+/)?.[0];
                    if (clubId) {
                        overdueClubs.push(clubId);
                    }
                }
            });

            if (overdueClubs.length === 0) {
                showNotification('warning', 'No Clubs Found', 'No overdue clubs found that need reminders.');
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            }

            Promise.all(overdueClubs.map(clubId => 
                fetch('{{ route('head-office.renewals.send-reminder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ club_id: clubId })
                })
            ))
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(results => {
                const successCount = results.filter(r => r.success).length;
                const totalMembers = results.reduce((sum, r) => {
                    if (r.success) {
                        const match = r.message.match(/(\d+) members/);
                        return sum + (match ? parseInt(match[1]) : 0);
                    }
                    return sum;
                }, 0);
                
                showNotification('success', 'Reminders Sent', `Sent renewal reminders to ${overdueClubs.length} clubs (${totalMembers} total members).`);
                button.innerHTML = originalText;
                button.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', 'An error occurred while sending bulk reminders.');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    }
    </script>
</x-dashboard-layout>
