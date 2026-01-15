<x-dashboard-layout>
    <x-slot name="title">Head Office Dashboard</x-slot>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-600 mt-2">Monitor your club management system at a glance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Organizations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">ORGANIZATIONS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOrganizations }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Registrations -->
        <a href="{{ route('head-office.approvals') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">NEW REGISTRATIONS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $newRegistrations }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">PENDING RENEWALS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingRenewalOrganizations }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Notifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">PENDING NOTIFICATIONS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2" data-pending-notifications-main>{{ $pendingNotifications }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a3 3 0 00-3-3H5a3 3 0 00-3 3v2m0 0h3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">TOTAL MEMBERS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalMembers) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
                <a href="{{ route('head-office.organizations') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View all
                </a>
            </div>

            @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @if($activity['color'] === 'green')
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                @elseif($activity['color'] === 'blue')
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                @else
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500">No recent activity</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('head-office.approvals') }}" 
                   class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Review Applications</p>
                        <p class="text-sm text-gray-600">Verify pending registrations</p>
                    </div>
                </a>

                <a href="{{ route('head-office.organizations') }}" 
                   class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Manage Organizations</p>
                        <p class="text-sm text-gray-600">View active clubs and organizations</p>
                    </div>
                </a>

                <!-- Notification Management -->
                <div class="bg-red-50 rounded-lg p-3">
                    <div class="flex items-start">
                        <div class="p-2 bg-red-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a3 3 0 00-3-3H5a3 3 0 00-3 3v2m0 0h3"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 mb-2">Notification Management</p>
                            <p class="text-sm text-gray-600 mb-3">Clear pending notifications sent to clubs</p>
                            <div class="space-y-2">
                                <button onclick="clearAllNotifications()" 
                                        class="w-full text-left px-3 py-2 text-sm text-red-700 bg-red-100 hover:bg-red-200 rounded-md transition-colors">
                                    Clear All Pending (<span data-pending-notifications>{{ $pendingNotifications }}</span>)
                                </button>
                                <button onclick="clearRenewalReminders()" 
                                        class="w-full text-left px-3 py-2 text-sm text-orange-700 bg-orange-100 hover:bg-orange-200 rounded-md transition-colors">
                                    Clear Renewal Reminders (<span data-renewal-reminders>{{ $renewalReminders }}</span>)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organization Types & Quick Actions -->
        <div class="space-y-6">
            <!-- Organization Types -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Organization Types</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">Academic Clubs</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $academicClubs }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalOrganizations > 0 ? ($academicClubs / $totalOrganizations) * 100 : 0 }}%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">Interest Clubs</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $interestClubs }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalOrganizations > 0 ? ($interestClubs / $totalOrganizations) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>

<script>
function clearAllNotifications() {
    if (confirm('Are you sure you want to clear all pending notifications? This action cannot be undone.')) {
        // Disable the button to prevent multiple clicks
        const button = event.target;
        button.disabled = true;
        button.textContent = 'Clearing...';
        
        fetch('{{ route('head-office.notifications.clear-pending') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Update the UI instead of full page reload
                updateNotificationCounts(data.cleared_count);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing notifications: ' + error.message);
        })
        .finally(() => {
            // Re-enable the button and update its text
            button.disabled = false;
            const pendingElement = document.querySelector('[data-pending-notifications]');
            const currentCount = pendingElement ? pendingElement.textContent : '{{ $pendingNotifications }}';
            button.innerHTML = `Clear All Pending (${currentCount})`;
        });
    }
}

function clearRenewalReminders() {
    if (confirm('Are you sure you want to clear all renewal reminder notifications? This action cannot be undone.')) {
        // Disable the button to prevent multiple clicks
        const button = event.target;
        button.disabled = true;
        button.textContent = 'Clearing...';
        
        fetch('{{ route('head-office.notifications.clear-by-type') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                type: 'renewal_reminder'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Update the UI instead of full page reload
                updateRenewalReminderCount(data.cleared_count);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing renewal reminders: ' + error.message);
        })
        .finally(() => {
            // Re-enable the button and update its text
            button.disabled = false;
            const renewalElement = document.querySelector('[data-renewal-reminders]');
            const currentCount = renewalElement ? renewalElement.textContent : '{{ $renewalReminders }}';
            button.innerHTML = `Clear Renewal Reminders (${currentCount})`;
        });
    }
}

function updateNotificationCounts(clearedCount) {
    // Update pending notifications count in buttons
    const pendingElement = document.querySelector('[data-pending-notifications]');
    if (pendingElement) {
        const currentCount = parseInt(pendingElement.textContent) || 0;
        const newCount = Math.max(0, currentCount - clearedCount);
        pendingElement.textContent = newCount;
    }
    
    // Update main pending notifications display
    const mainPendingElement = document.querySelector('[data-pending-notifications-main]');
    if (mainPendingElement) {
        const currentCount = parseInt(mainPendingElement.textContent) || 0;
        const newCount = Math.max(0, currentCount - clearedCount);
        mainPendingElement.textContent = newCount;
    }
    
    // Update total notifications if displayed
    const totalElement = document.querySelector('[data-total-notifications]');
    if (totalElement) {
        const currentTotal = parseInt(totalElement.textContent) || 0;
        const newTotal = Math.max(0, currentTotal - clearedCount);
        totalElement.textContent = newTotal;
    }
}

function updateRenewalReminderCount(clearedCount) {
    // Update renewal reminder count
    const renewalElement = document.querySelector('[data-renewal-reminders]');
    if (renewalElement) {
        const currentCount = parseInt(renewalElement.textContent) || 0;
        const newCount = Math.max(0, currentCount - clearedCount);
        renewalElement.textContent = newCount;
    }
    
    // Also update pending notifications count
    updateNotificationCounts(clearedCount);
}
</script>
