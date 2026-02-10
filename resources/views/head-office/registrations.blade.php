<x-dashboard-layout>
    <x-slot name="title">Registration Requests</x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Registration Requests</h1>
                <p class="text-gray-600 mt-1">Review and manage club registration applications</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">{{ $registrations->count() }} pending requests</span>
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

                                <!-- Status and Actions -->
                                <div class="flex flex-col items-end space-y-3 ml-4">
                                    <!-- Approval Progress -->
                                    <div class="text-right">
                                        @php
                                            $approvals = collect([
                                                $registration->verified_by_osa,
                                                $registration->noted_by_director,
                                                $registration->approved_by_vp,
                                                $registration->endorsed_by_dean
                                            ])->filter()->count();
                                        @endphp
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs text-gray-500">Approvals:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $approvals }}/4</span>
                                        </div>
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ ($approvals / 4) * 100 }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($registration->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>

                                    <!-- Action Button -->
                                    <a href="{{ route('head-office.registrations.show', $registration) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No pending registration requests</h3>
                <p class="text-gray-500">All registration requests have been processed.</p>
            </div>
        @endif
    </div>
</x-dashboard-layout>




