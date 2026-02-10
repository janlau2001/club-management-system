<x-dashboard-layout>
    <x-slot name="title">Head Office Dashboard</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor your club management system at a glance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Organizations -->
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">ORGANIZATIONS</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $totalOrganizations }}</p>
            </div>
        </div>

        <!-- New Registrations -->
        <a href="{{ route('head-office.approvals') }}" class="block bg-white border border-gray-200 p-5 hover:border-gray-400 transition-colors">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">NEW REGISTRATIONS</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $newRegistrations }}</p>
            </div>
        </a>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">PENDING RENEWALS</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $pendingRenewalOrganizations }}</p>
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">TOTAL MEMBERS</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalMembers) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-base font-semibold text-gray-900">Recent Activity</h2>
                <a href="{{ route('head-office.organizations') }}" class="text-gray-900 hover:text-gray-700 text-sm font-medium underline">
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
        <div class="bg-white border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('head-office.approvals') }}" 
                   class="flex items-center p-3 border border-gray-200 hover:border-gray-900 transition-colors">
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
                   class="flex items-center p-3 border border-gray-200 hover:border-gray-900 transition-colors">
                    <div class="mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Manage Organizations</p>
                        <p class="text-sm text-gray-600">View active clubs and organizations</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Organization Types & Quick Actions -->
        <div class="space-y-6">
            <!-- Organization Types -->
            <div class="bg-white border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Organization Types</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600">Academic Clubs</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $academicClubs }}</span>
                    </div>
                    <div class="w-full bg-gray-200 h-1">
                        <div class="bg-gray-900 h-1" style="width: {{ $totalOrganizations > 0 ? ($academicClubs / $totalOrganizations) * 100 : 0 }}%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600">Interest Clubs</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $interestClubs }}</span>
                    </div>
                    <div class="w-full bg-gray-200 h-1">
                        <div class="bg-gray-900 h-1" style="width: {{ $totalOrganizations > 0 ? ($interestClubs / $totalOrganizations) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
