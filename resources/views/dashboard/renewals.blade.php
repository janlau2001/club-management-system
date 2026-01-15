<x-dashboard-layout>
    <x-slot name="title">Club Renewals</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Club Renewals</h1>
            <p class="text-gray-600 mt-2">Manage organization renewal applications and track renewal status</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export Report</span>
            </button>
            <button onclick="sendBulkReminders()" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Send Reminders</span>
            </button>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Upcoming Renewals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $upcomingRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Due in 30 days</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Due Now</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $dueRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Due for renewal</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Overdue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overdueRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Past due date</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Submitted This Year</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $submittedRenewals ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Renewal requests</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('dashboard.renewals') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search organizations..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Renewal Status</label>
                    <select name="renewal_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Clubs</option>
                        <option value="upcoming" {{ request('renewal_status') == 'upcoming' ? 'selected' : '' }}>Upcoming (Due in 30 days)</option>
                        <option value="due" {{ request('renewal_status') == 'due' ? 'selected' : '' }}>Due Now</option>
                        <option value="overdue" {{ request('renewal_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="recent" {{ request('renewal_status') == 'recent' ? 'selected' : '' }}>Recently Renewed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Renewals List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Club Renewal Status</h3>
                <div class="text-sm text-gray-500">
                    {{ $renewalData->count() ?? 0 }} clubs
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Last Renewal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Until Due</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Renewal Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($renewalData ?? [] as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $departmentColors = [
                                            'SASTE' => 'bg-blue-100 text-blue-600',
                                            'SBAHM' => 'bg-green-100 text-green-600',
                                            'SNAHS' => 'bg-red-100 text-red-600',
                                            'SITE' => 'bg-purple-100 text-purple-600',
                                            'BEU' => 'bg-yellow-100 text-yellow-600',
                                            'SOM' => 'bg-gray-100 text-gray-600',
                                            'GRADUATE SCHOOL' => 'bg-gray-100 text-gray-600'
                                        ];
                                        $colorClass = $departmentColors[$item->department] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <div class="{{ $colorClass }} p-2 rounded-lg mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->club_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->department }} • {{ $item->member_count }} members</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->date_registered ? $item->date_registered->format('M j, Y') : 'Not set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->last_renewal_date ? $item->last_renewal_date->format('M j, Y') : 'Never renewed' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($item->days_until_due > 0)
                                    <span class="text-blue-600">Due in {{ (int)$item->days_until_due }} days</span>
                                @elseif($item->days_until_due === 0)
                                    <span class="text-orange-600 font-medium">Due Today</span>
                                @else
                                    <span class="text-red-600 font-medium">Overdue by {{ (int)abs($item->days_until_due) }} days</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->has_submitted)
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        {{ $item->submission_status }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Not Submitted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('dashboard.organization.show', $item->club) }}" class="text-blue-600 hover:text-blue-900">View Club</a>
                                @if($item->has_submitted)
                                    <a href="{{ route('dashboard.renewals.show', $item->submitted_renewal->id) }}" class="text-green-600 hover:text-green-900">View Renewal</a>
                                @endif
                                @if(in_array($item->renewal_status, ['upcoming', 'due', 'overdue']) && !$item->has_submitted)
                                    <button class="text-orange-600 hover:text-orange-900" onclick="sendRenewalReminder({{ $item->club->id }})">Send Reminder</button>
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

    <!-- JavaScript for reminder functionality -->
    <script>
    function sendRenewalReminder(clubId) {
        if (confirm('Send renewal reminder to all members of this club?')) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Sending...';
            button.disabled = true;

            fetch('{{ route('dashboard.renewals.send-reminder') }}', {
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
                    alert(data.message);
                    // Optionally disable the button for some time to prevent spam
                    button.textContent = 'Sent ✓';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 5000);
                } else {
                    alert('Failed to send reminder. Please try again.');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the reminder.');
                button.textContent = originalText;
                button.disabled = false;
            });
        }
    }

    function sendBulkReminders() {
        if (confirm('Send renewal reminders to all overdue clubs? This will notify all members of overdue clubs.')) {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Sending...';
            button.disabled = true;

            // Get all overdue clubs from the table
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
                alert('No overdue clubs found that need reminders.');
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            }

            // Send reminders to all overdue clubs
            Promise.all(overdueClubs.map(clubId => 
                fetch('{{ route('dashboard.renewals.send-reminder') }}', {
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
                
                alert(`Sent renewal reminders to ${overdueClubs.length} clubs (${totalMembers} total members).`);
                button.innerHTML = originalText;
                button.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending bulk reminders.');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    }
    </script>
</x-dashboard-layout>
