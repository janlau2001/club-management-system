<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $club->name }} - {{ $clubUser->role === 'adviser' ? 'Adviser' : 'Officer' }} Dashboard</title>
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
                    <h1 class="text-2xl font-bold text-white">{{ $club->name }}</h1>
                    <p class="text-white opacity-90">{{ $club->department }} • {{ $club->club_type }}</p>
                    <p class="text-white opacity-75 text-sm">Welcome, {{ $clubUser->name }} ({{ $clubUser->getDisplayRole() }})</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30 shadow-lg">
                            <div class="w-8 h-8 bg-white/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $clubUser->name }}</p>
                                <p class="text-xs opacity-75">{{ $clubUser->getDisplayRole() }}</p>
                            </div>
                            <svg class="w-4 h-4 text-white" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                            <!-- Profile Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ $clubUser->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $clubUser->email }}</p>
                                <p class="text-xs text-gray-400">{{ $clubUser->year_level }} • {{ $clubUser->department }}</p>
                            </div>

                            <!-- Menu Items -->
                            <a href="{{ route('club.officer.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Profile
                            </a>

                            <form method="POST" action="{{ route('club.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Club Suspension Warning -->
        @if($club->status === 'suspended')
            <div class="mx-6 mt-4 bg-red-50 border-2 border-red-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-red-800">Club Currently Suspended</h3>
                        <p class="text-red-700 mt-1">
                            <strong>{{ $club->name }}</strong> is currently suspended by the administration. 
                            During suspension, only Presidents, Vice Presidents, and Advisers have access to the system.
                        </p>
                        <p class="text-red-600 text-sm mt-2">
                            Regular members and other officers cannot access the system until the club is reactivated.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($club->status === 'suspended')
            <!-- Violation Appeals Section (Outside Alert Box) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Violation Appeals</h3>
                        <p class="text-gray-600 text-sm">Review and appeal club violations</p>
                    </div>
                    <a 
                        href="{{ route('club.officer.violations') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        📋 View & Appeal Violations
                    </a>
                </div>
            </div>
        @endif

        <main class="p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Members</p>
                                <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalMembers }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Officers</p>
                                <p class="text-3xl font-bold text-green-800 mt-2">{{ $totalOfficers }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Members</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $onlineMembersCount }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Officers</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $onlineOfficersCount }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column - Club Management -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Club News Section -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Club News & Announcements</h2>
                                        <p class="text-gray-600 mt-1">Manage club updates and announcements</p>
                                    </div>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Add News
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No News Yet</h3>
                                    <p class="text-gray-500 mb-4">Start sharing updates with your club members.</p>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                        Create First News Post
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Club Activities Section -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Club Activities & Events</h2>
                                        <p class="text-gray-600 mt-1">Plan and manage club activities</p>
                                    </div>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Add Activity
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Scheduled</h3>
                                    <p class="text-gray-500 mb-4">Start planning activities for your club members.</p>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                        Schedule First Activity
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Member Management -->
                    <div class="space-y-8">
                        
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">
                                    @if($clubUser->role === 'member')
                                        Member Actions
                                    @else
                                        Officer Actions
                                    @endif
                                </h2>
                                <p class="text-gray-600 mt-1">
                                    @if($clubUser->role === 'member')
                                        View club information
                                    @else
                                        Manage your club
                                    @endif
                                </p>
                            </div>

                            <div class="p-6 space-y-3">
                                @if($clubUser->role === 'member')
                                    <!-- Member Actions -->
                                    <a href="{{ route('club.officer.manage-members') }}"
                                       class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span>View Members</span>
                                        </div>
                                    </a>
                                @else
                                    <!-- Management Actions (All Officers and Advisers) -->
                                    @if($clubUser->role === 'officer' || $clubUser->role === 'adviser')
                                        <a href="{{ $club->status === 'suspended' ? '#' : route('club.officer.manage-members') }}"
                                           class="block w-full bg-blue-50 {{ $club->status === 'suspended' ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-100' }} text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center mb-3 {{ $club->status === 'suspended' ? 'pointer-events-none' : '' }}">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                <span>Manage Members</span>
                                            </div>
                                        </a>
                                    @else
                                        <!-- Regular Members can only view members -->
                                        <a href="{{ route('club.officer.view-members') }}"
                                           class="block w-full bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                <span>View Members</span>
                                            </div>
                                        </a>
                                    @endif

                                    <!-- Club Renewal (All Officers and Advisers) -->
                                    @if($clubUser->role === 'officer' || $clubUser->role === 'adviser')
                                        <a href="{{ $club->status === 'suspended' ? '#' : route('club.officer.renewal') }}"
                                           class="block w-full bg-orange-50 {{ $club->status === 'suspended' ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100' }} text-orange-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center {{ $club->status === 'suspended' ? 'pointer-events-none' : '' }}">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                <span>Club Renewal</span>
                                            </div>
                                        </a>
                                    @endif

                                    <!-- Officer and Adviser Special Actions -->
                                    @if($clubUser->role === 'officer' || $clubUser->role === 'adviser')
                                        <div class="border-t border-gray-200 pt-3 mt-3 relative">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">
                                                Officer Actions
                                            </p>

                                            <!-- Actions Container (blurred when suspended) -->
                                            <div class="{{ $club->status === 'suspended' ? 'filter blur-sm pointer-events-none' : '' }}">
                                                <a href="{{ route('club.officer.renewal.status') }}"
                                                   class="block w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center mb-3">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>Renewal Approvals</span>
                                                    </div>
                                                </a>

                                                <a href="{{ route('club.officer.applicants') }}"
                                                   class="block w-full bg-teal-50 hover:bg-teal-100 text-teal-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center mb-3 relative">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                        </svg>
                                                        <span>Review Club Applicants</span>
                                                    </div>
                                                    @if($newApplicationsCount > 0)
                                                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[20px]">
                                                            {{ $newApplicationsCount }}
                                                        </span>
                                                    @endif
                                                </a>

                                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <div>
                                                            <p class="text-xs font-medium text-blue-800">Officer Authority</p>
                                                            <p class="text-xs text-blue-700">You have full management access</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($clubUser->role === 'adviser')
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-3">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs font-medium text-green-800">Adviser Authority</p>
                                                                <p class="text-xs text-green-700">You can certify renewal applications</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Suspension Lock Overlay -->
                                            @if($club->status === 'suspended')
                                                <div class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-lg">
                                                    <div class="text-center px-4 py-6">
                                                        <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Actions Locked</h3>
                                                        <p class="text-xs text-gray-600">Officer actions are disabled during suspension</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- Online Officers -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Officers</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineOfficersCount }} online</p>
                            </div>
                            
                            <div class="p-6">
                                @if($onlineOfficers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineOfficers as $officer)
                                            <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">{{ $officer->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $officer->position ?? 'Officer' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">No officers online</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Online Members -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Members</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineMembersCount }} online</p>
                            </div>
                            
                            <div class="p-6 max-h-96 overflow-y-auto">
                                @if($onlineMembers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineMembers as $member)
                                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $member->year_level }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">No members online</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Violation Appeal Modal -->
    <div id="violationAppealModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-[600px] shadow-xl rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900">Appeal Club Violations</h3>
                        <p class="text-sm text-gray-500">Submit appeals with supporting documents</p>
                    </div>
                </div>
                <button onclick="closeViolationAppealModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Loading state for violations -->
            <div id="violationsLoading" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading violations...</p>
            </div>

            <!-- Violations list -->
            <div id="violationsList" class="hidden space-y-4">
                <!-- Violations will be loaded dynamically -->
            </div>

            <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                <button 
                    onclick="closeViolationAppealModal()" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Individual Appeal Form Modal -->
    <div id="appealFormModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-[500px] shadow-xl rounded-lg bg-white">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">Submit Appeal</h3>
                    <p class="text-sm text-gray-500" id="appealViolationTitle">Appeal for violation</p>
                </div>
            </div>
            
            <form id="appealForm" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" id="violationId" name="violation_id">
                
                <!-- Appeal Reason -->
                <div class="space-y-2">
                    <label for="appeal_reason" class="block text-sm font-medium text-gray-700">
                        Appeal Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="appeal_reason" 
                        name="appeal_reason" 
                        required 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Provide detailed explanation of why this violation should be reconsidered..."
                    ></textarea>
                </div>

                <!-- Supporting Documents -->
                <div class="space-y-2">
                    <label for="supporting_documents" class="block text-sm font-medium text-gray-700">
                        Supporting Documents <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        id="supporting_documents" 
                        name="supporting_documents[]" 
                        multiple 
                        accept=".pdf,.doc,.docx" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                    <p class="text-xs text-gray-500">Upload PDF or Word documents. Multiple files allowed.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closeAppealFormModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors font-medium"
                    >
                        Submit Appeal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Auto-refresh online status every 30 seconds -->
    <script>
        setInterval(function() {
            fetch('/club/refresh-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            }).catch(() => {
                // Ignore errors
            });
        }, 30000);

        // Violation Appeal Modal Functions
        function showViolationAppealModal() {
            // Redirect to the violations modal
            showViolationsModal();
        }

        function showViolationsModal() {
            document.getElementById('violationAppealModal').classList.remove('hidden');
            loadClubViolations();
        }

        function closeViolationsModal() {
            document.getElementById('violationAppealModal').classList.add('hidden');
        }

        function closeViolationAppealModal() {
            document.getElementById('violationAppealModal').classList.add('hidden');
        }

        function submitQuickAppeal(violationId) {
            console.log('submitQuickAppeal called with:', violationId);
            
            // Get the title from the button's data attribute
            const button = document.querySelector(`button[onclick*="submitQuickAppeal(${violationId})"]`);
            const violationTitle = button ? button.getAttribute('data-title') : 'Unknown Violation';
            
            const reason = prompt(`Submit appeal for "${violationTitle}".\n\nPlease provide a brief reason for your appeal:`, 'We believe this violation was issued in error and request a review.');
            
            console.log('User entered reason:', reason);
            
            if (!reason || reason.trim() === '') {
                console.log('No reason provided, cancelling');
                return;
            }

            if (button) {
                const originalText = button.textContent;
                button.textContent = 'Submitting...';
                button.disabled = true;
                
                console.log('Submitting appeal...');

                fetch('/club/officer/submit-appeal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        violation_id: violationId,
                        reason: reason.trim()
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        showNotificationModal('Success!', 'Appeal submitted successfully!', 'success');
                        loadClubViolations(); // Refresh the violations list
                    } else {
                        showNotificationModal('Error', data.error || 'Unknown error occurred', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotificationModal('Error', 'Error submitting appeal. Please try again.', 'error');
                })
                .finally(() => {
                    if (button) {
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                });
            } else {
                console.error('Could not find button for violation', violationId);
            }
        }

        function showAppealFormModal(violationId, violationTitle) {
            document.getElementById('violationId').value = violationId;
            document.getElementById('appealViolationTitle').textContent = 'Appeal for: ' + violationTitle;
            document.getElementById('appealFormModal').classList.remove('hidden');
        }

        function closeAppealFormModal() {
            document.getElementById('appealFormModal').classList.add('hidden');
            document.getElementById('appealForm').reset();
        }

        function loadClubViolations() {
            const loadingDiv = document.getElementById('violationsLoading');
            const listDiv = document.getElementById('violationsList');
            
            loadingDiv.classList.remove('hidden');
            listDiv.classList.add('hidden');

            fetch('/club/officer/violations')
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        displayViolations(data.violations);
                    } else {
                        listDiv.innerHTML = '<p class="text-center text-gray-500 py-8">Failed to load violations: ' + (data.error || 'Unknown error') + '</p>';
                    }
                    loadingDiv.classList.add('hidden');
                    listDiv.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    listDiv.innerHTML = '<p class="text-center text-red-500 py-8">Error loading violations: ' + error.message + '</p>';
                    loadingDiv.classList.add('hidden');
                    listDiv.classList.remove('hidden');
                });
        }

        function displayViolations(violations) {
            const listDiv = document.getElementById('violationsList');
            
            if (violations.length === 0) {
                listDiv.innerHTML = '<p class="text-center text-gray-500 py-8">No violations found</p>';
                return;
            }

            const violationsHtml = violations.map(violation => `
                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${violation.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${violation.description}</p>
                        </div>
                        <div class="ml-4 flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${violation.severity_color}">
                                ${violation.severity.charAt(0).toUpperCase() + violation.severity.slice(1)}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${violation.status_color}">
                                ${violation.status.charAt(0).toUpperCase() + violation.status.slice(1)}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="text-xs text-gray-500">
                            <p>Date: ${new Date(violation.violation_date).toLocaleDateString()}</p>
                            <p>Points: ${violation.points}</p>
                        </div>
                        <div class="space-x-2">
                            ${violation.can_appeal ? `
                                <button 
                                    onclick="submitQuickAppeal(${violation.id})"
                                    data-title="${violation.title}"
                                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
                                >
                                    Appeal
                                </button>
                            ` : violation.appeal_status ? `
                                <span class="px-3 py-1 text-xs rounded-full ${violation.appeal_status_color}">
                                    Appeal ${violation.appeal_status}
                                </span>
                            ` : `
                                <span class="text-gray-400 text-sm">Cannot appeal</span>
                            `}
                                <span class="text-gray-400 text-sm">
                                    ${violation.appeal_status ? 'Appeal: ' + violation.appeal_status : 'Cannot appeal'}
                                </span>
                            `}
                        </div>
                    </div>
                </div>
            `).join('');

            listDiv.innerHTML = violationsHtml;
        }

        // Handle appeal form submission
        document.getElementById('appealForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            
            fetch('/club/officer/submit-appeal', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotificationModal('Success!', 'Appeal submitted successfully!', 'success');
                    closeAppealFormModal();
                    closeViolationAppealModal();
                } else {
                    showNotificationModal('Error', data.message || 'Error submitting appeal', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotificationModal('Error', 'An error occurred while submitting the appeal', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Submit Appeal';
            });
        });

        // Simple violation appeal functions - Make them globally accessible
        window.showViolationsList = function() {
            const listDiv = document.getElementById('violationsList');
            listDiv.classList.remove('hidden');
            loadViolations();
        }

        function loadViolations() {
            const contentDiv = document.getElementById('violationsContent');
            contentDiv.innerHTML = '<p class="text-gray-500">Loading violations...</p>';

            fetch('/club/officer/violations')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.violations.length > 0) {
                        let html = '<div class="space-y-3">';
                        data.violations.forEach(violation => {
                            html += `
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900">${violation.title}</h5>
                                            <p class="text-sm text-gray-600 mt-1">${violation.description}</p>
                                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                                <span>Date: ${violation.violation_date}</span>
                                                <span>Points: ${violation.points}</span>
                                                <span class="px-2 py-1 rounded ${violation.severity_color}">${violation.severity}</span>
                                                <span class="px-2 py-1 rounded ${violation.status_color}">${violation.status}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            ${violation.can_appeal ? 
                                                `<button onclick="appealViolation(${violation.id})" 
                                                         class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                    Appeal
                                                 </button>` : 
                                                violation.appeal_status ? 
                                                `<span class="px-2 py-1 text-xs rounded ${violation.appeal_status_color}">Appeal ${violation.appeal_status}</span>` :
                                                `<span class="text-xs text-gray-500">Cannot appeal</span>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        contentDiv.innerHTML = html;
                    } else {
                        contentDiv.innerHTML = '<p class="text-gray-500">No violations found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading violations:', error);
                    contentDiv.innerHTML = '<p class="text-red-500">Error loading violations.</p>';
                });
        }

        window.appealViolation = function(violationId) {
            const reason = prompt('Please provide your reason for appealing this violation:', 'We believe this violation was issued in error.');
            
            if (!reason || reason.trim() === '') {
                return;
            }

            fetch('/club/officer/submit-appeal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    violation_id: violationId,
                    reason: reason.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotificationModal('Success!', 'Appeal submitted successfully!', 'success');
                    loadViolations(); // Refresh the list
                } else {
                    showNotificationModal('Error', data.error || 'Unknown error', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotificationModal('Error', 'Error submitting appeal.', 'error');
            });
        }
    </script>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div id="notificationIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full">
                    <!-- Icon will be injected here -->
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 id="notificationTitle" class="text-lg font-medium text-gray-900 text-center"></h3>
                    <p id="notificationMessage" class="text-sm text-gray-600 mt-2 text-center"></p>
                    <div class="flex items-center justify-center mt-4">
                        <button type="button" onclick="hideNotificationModal()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showNotificationModal(title, message, type = 'success') {
            const modal = document.getElementById('notificationModal');
            const icon = document.getElementById('notificationIcon');
            const titleEl = document.getElementById('notificationTitle');
            const messageEl = document.getElementById('notificationMessage');

            titleEl.textContent = title;
            messageEl.textContent = message;

            // Set icon based on type
            if (type === 'success') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else if (type === 'warning') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            }

            modal.classList.remove('hidden');
        }

        function hideNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
        }
    </script>
</body>
</html>
