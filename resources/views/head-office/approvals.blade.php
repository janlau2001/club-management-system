<x-dashboard-layout>
    <x-slot name="title">Registration Monitoring</x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Registration Monitoring</h1>
                <p class="text-gray-600 mt-1">Monitor club registration applications and their approval status</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Status Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <!-- Pending Tab -->
                    <a href="{{ route('head-office.approvals', ['status' => 'pending']) }}" 
                       class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                              {{ $status === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $status === 'pending' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium
                                     {{ $status === 'pending' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $pendingCount }}
                        </span>
                    </a>

                    <!-- Approved Tab -->
                    <a href="{{ route('head-office.approvals', ['status' => 'approved']) }}" 
                       class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                              {{ $status === 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $status === 'approved' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approved
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium
                                     {{ $status === 'approved' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $approvedCount }}
                        </span>
                    </a>

                    <!-- Rejected Tab -->
                    <a href="{{ route('head-office.approvals', ['status' => 'rejected']) }}" 
                       class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                              {{ $status === 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $status === 'rejected' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Rejected
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium
                                     {{ $status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $rejectedCount }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Tab Content Description -->
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        @if($status === 'pending')
                            <span class="font-medium text-gray-900">{{ $registrations->count() }}</span> pending registration(s) awaiting verification
                        @elseif($status === 'approved')
                            <span class="font-medium text-gray-900">{{ $registrations->count() }}</span> approved registration(s)
                        @else
                            <span class="font-medium text-gray-900">{{ $registrations->count() }}</span> rejected registration(s)
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if($registrations->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($registrations as $registration)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Club Avatar -->
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-full p-3 flex-shrink-0">
                                        <span class="text-white font-bold text-lg">
                                            {{ strtoupper(substr($registration->club_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Club Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-xl font-semibold text-gray-900 truncate">{{ $registration->club_name }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $registration->nature === 'Academic' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $registration->nature }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span>{{ $registration->department }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ $registration->officer->name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $registration->created_at->format('M j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $registration->recommended_adviser }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Status and Current Step -->
                                <div class="flex flex-col items-end space-y-3 ml-4">
                                    <!-- Overall Status Badge -->
                                    @if($registration->status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Fully Approved
                                        </span>
                                    @elseif($registration->status === 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            In Progress
                                        </span>
                                    @endif

                                    <!-- Current Step Info -->
                                    <div class="text-right text-xs text-gray-600">
                                        @if($registration->status === 'approved')
                                            <span class="text-green-600 font-medium">All approvals completed</span>
                                        @elseif($registration->status === 'rejected')
                                            <span class="text-red-600 font-medium">Registration rejected</span>
                                        @else
                                            <span class="font-medium">Current Step:</span><br>
                                            @if($registration->approved_by_vp)
                                                <span class="text-blue-600">Ready for final approval</span>
                                            @elseif($registration->noted_by_director)
                                                <span class="text-blue-600">Awaiting VP approval</span>
                                            @elseif($registration->approved_by_psg_council)
                                                <span class="text-blue-600">Awaiting Director noting</span>
                                            @elseif($registration->endorsed_by_dean)
                                                <span class="text-blue-600">Awaiting PSG Council approval</span>
                                            @else
                                                <span class="text-blue-600">Awaiting Dean endorsement</span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- View Action -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('head-office.approvals.show', $registration) }}"
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
                        <div class="text-center py-16">
                <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No registrations found</h3>
                <p class="text-gray-500">No club registration applications have been submitted yet.</p>
            </div>
        @endif
    </div>
</x-dashboard-layout>
