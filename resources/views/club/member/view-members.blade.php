<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $club->name }} - View Members</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $club->name }} - Members</h1>
                    <p class="text-white opacity-90">{{ $club->department }} • {{ $club->club_type }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('club.member.dashboard') }}"
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Member List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Club Members</h2>
                    <p class="text-gray-600 mt-1">All members and officers of {{ $club->name }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($clubUsers as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $member->role === 'adviser' ? 'bg-purple-100 text-purple-800' : 
                                               ($member->role === 'officer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                        @if($member->position)
                                            <div class="text-xs text-gray-500 mt-1">{{ $member->position }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($member->role === 'adviser')
                                            {{ $member->professor_id ?? 'N/A' }}
                                        @else
                                            {{ $member->student_id }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $member->department }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $member->year_level }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->is_online && $member->last_activity >= now()->subMinutes(5))
                                            <div class="flex items-center">
                                                <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <div class="h-2 w-2 bg-gray-300 rounded-full mr-2"></div>
                                                <span class="text-xs text-gray-500">Offline</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-2">No Members Found</p>
                                        <p class="text-gray-500">This club doesn't have any members yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary Footer -->
                @if($clubUsers->count() > 0)
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <div>
                                Total: {{ $clubUsers->count() }} members
                                ({{ $clubUsers->where('role', 'officer')->count() }} officers, 
                                {{ $clubUsers->where('role', 'member')->count() }} members,
                                {{ $clubUsers->where('role', 'adviser')->count() }} advisers)
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                    <span>{{ $clubUsers->where('is_online', true)->where('last_activity', '>=', now()->subMinutes(5))->count() }} Online</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-gray-300 rounded-full mr-2"></div>
                                    <span>{{ $clubUsers->where('is_online', false)->count() + $clubUsers->where('last_activity', '<', now()->subMinutes(5))->count() }} Offline</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
