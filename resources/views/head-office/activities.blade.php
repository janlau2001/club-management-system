<x-dashboard-layout>
    <x-slot name="title">Recent Activities</x-slot>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Recent Activities</h1>
                <p class="text-gray-600 mt-2">All activities from the last 24 hours</p>
            </div>
            <a href="{{ route('head-office.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Activities List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        @if($allActivities->count() > 0)
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($allActivities as $activity)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="bg-{{ $activity['color'] }}-100 p-3 rounded-full flex-shrink-0">
                                @if($activity['type'] === 'approved' || $activity['type'] === 'resumed')
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($activity['type'] === 'registration')
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                    </svg>
                                @elseif($activity['type'] === 'suspended')
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($activity['type'] === 'renewal_pending')
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-base font-semibold text-gray-900">{{ $activity['title'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                        
                                        @if(isset($activity['details']))
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-gray-500">
                                                @if(isset($activity['details']['officer']))
                                                    <div>
                                                        <span class="font-medium">Officer:</span> {{ $activity['details']['officer'] }}
                                                    </div>
                                                @endif
                                                @if(isset($activity['details']['department']))
                                                    <div>
                                                        <span class="font-medium">Department:</span> {{ $activity['details']['department'] }}
                                                    </div>
                                                @endif
                                                @if(isset($activity['details']['member_count']))
                                                    <div>
                                                        <span class="font-medium">Members:</span> {{ $activity['details']['member_count'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($activity['color'] === 'green') bg-green-100 text-green-800
                                            @elseif($activity['color'] === 'blue') bg-blue-100 text-blue-800
                                            @elseif($activity['color'] === 'red') bg-red-100 text-red-800
                                            @elseif($activity['color'] === 'yellow') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $activity['type'])) }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No recent activities</h3>
                <p class="text-gray-500">No activities have occurred in the last 24 hours.</p>
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    @if($allActivities->count() > 0)
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Approved</p>
                        <p class="text-lg font-bold text-green-900">{{ $allActivities->where('type', 'approved')->count() + $allActivities->where('type', 'resumed')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">New Registrations</p>
                        <p class="text-lg font-bold text-blue-900">{{ $allActivities->where('type', 'registration')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Suspended</p>
                        <p class="text-lg font-bold text-red-900">{{ $allActivities->where('type', 'suspended')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Pending Renewal</p>
                        <p class="text-lg font-bold text-yellow-900">{{ $allActivities->where('type', 'renewal_pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-dashboard-layout>
