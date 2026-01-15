<x-dashboard-layout>
    <x-slot name="title">{{ $club->name }} - Organization Details</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('dashboard.organizations') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Organizations
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $club->name }}</h1>
                <p class="text-gray-600">{{ $club->department }}</p>
            </div>
            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $club->status_badge }}">
                {{ ucfirst(str_replace('_', ' ', $club->status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Club Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Club Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Department</label>
                            <p class="mt-1 text-gray-900">{{ $club->department }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date Registered</label>
                            <p class="mt-1 text-gray-900">{{ $club->date_registered->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Members</label>
                            <p class="mt-1 text-gray-900">{{ $club->clubUsers->count() }} ({{ $club->clubUsers->where('role', 'officer')->count() }} officers, {{ $club->clubUsers->where('role', 'member')->count() }} members)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $club->status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($club->description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="mt-1 text-gray-900">{{ $club->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Officers -->
                @php
                    $officers = $club->clubUsers->where('role', 'officer');
                @endphp
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Club Officers ({{ $officers->count() }})</h2>
                    <div class="space-y-3">
                        @forelse($officers as $officer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $officer->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $officer->position }}</p>
                                <p class="text-sm text-gray-500">{{ $officer->department }} • {{ $officer->year_level }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $officer->email }}</p>
                                @if($officer->student_id)
                                    <p class="text-sm text-gray-500">ID: {{ $officer->student_id }}</p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No officers assigned yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Members -->
                @php
                    $members = $club->clubUsers->where('role', 'member');
                @endphp
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Club Members ({{ $members->count() }})</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $member->student_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $member->year_level }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->isOnline())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Online
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                                Offline
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No members found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Adviser Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Club Adviser</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="mt-1 text-gray-900">{{ $club->adviser_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $club->adviser_email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Officers</span>
                            <span class="font-medium">{{ $club->officers->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Members</span>
                            <span class="font-medium">{{ $club->members->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Years Active</span>
                            <span class="font-medium">{{ now()->year - $club->date_registered->year }} years</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
