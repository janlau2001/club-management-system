<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Officer Dashboard - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Session Management Script -->
    <script>
        // Session management
        let sessionTimeout;
        let warningTimeout;
        let warningShown = false;

        // Session timeout (5 minutes = 300000ms)
        const SESSION_TIMEOUT = 300000;
        const WARNING_TIME = 60000; // Show warning 1 minute before timeout

        function resetSessionTimer() {
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            warningShown = false;

            // Set warning timer
            warningTimeout = setTimeout(() => {
                if (!warningShown) {
                    warningShown = true;
                    showSessionWarning();
                }
            }, SESSION_TIMEOUT - WARNING_TIME);

            // Set session timeout (hard logout)
            sessionTimeout = setTimeout(() => {
                alert('Session expired. You will be redirected to login.');
                performLogout();
            }, SESSION_TIMEOUT);
        }

        function showSessionWarning() {
            const userChoice = confirm('Your session will expire in 1 minute. Click OK to stay logged in or Cancel to logout.');

            if (userChoice) {
                // User wants to stay logged in, refresh session
                fetch('/refresh-session', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Session refresh failed');
                    }
                    // Reset the entire timer system
                    resetSessionTimer();
                }).catch(() => {
                    alert('Session refresh failed. You will be redirected to login.');
                    performLogout();
                });
            } else {
                // User chose to logout immediately
                performLogout();
            }
        }

        function performLogout() {
            // Clear all timers
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);

            // Create a form and submit logout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }

        // Set navigation flag before legitimate navigation
        function setNavigationFlag() {
            // Simplified - no server call needed
        }

        // Reset timer on page load
        document.addEventListener('DOMContentLoaded', resetSessionTimer);

        // Reset timer on user activity (but not too frequently)
        let lastActivity = Date.now();
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
            document.addEventListener(event, () => {
                const now = Date.now();
                if (now - lastActivity > 10000) { // Only reset every 10 seconds
                    lastActivity = now;
                    resetSessionTimer();
                }
            }, true);
        });

        // Handle legitimate navigation (set flag before navigation)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('logout')) {
                setNavigationFlag();
            }
        });

        // Handle browser back-forward cache gracefully
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Only reload if on login page
                if (window.location.pathname === '/login') {
                    window.location.reload();
                }
            }
        });

        // Handle page refresh (this will trigger session expiration)
        if (performance.navigation.type === 1) {
            // This is a page refresh, session should expire
            performLogout();
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
            <div class="flex items-center justify-between px-6 py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Officer Dashboard</h1>
                    <p class="text-blue-100 mt-1">Welcome back, {{ $officer->name }}</p>
                    <p class="text-blue-200 text-sm">{{ $officer->department }} • {{ $officer->year_level }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right text-white">
                        <p class="text-sm opacity-90">{{ $officer->email }}</p>
                        <p class="text-xs opacity-75">{{ $officer->club_status }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-blue-700 hover:bg-blue-900 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors shadow-md">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Applications</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalRegistrations }}</p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pending</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingRegistrations }}</p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Approved</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $approvedRegistrations }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rejected</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejectedRegistrations }}</p>
                            </div>
                            <div class="bg-red-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Left Column - Registration Applications -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Current Registrations Section -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Registration Applications</h2>
                                        <p class="text-gray-600 mt-1">Track your club registration submissions</p>
                                    </div>
                                    <a href="{{ route('officer.club-registration.create') }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        New Application
                                    </a>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($registrations->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($registrations as $registration)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold text-gray-900">{{ $registration->club_name }}</h3>
                                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                                            <span>{{ $registration->department }}</span>
                                                            <span>•</span>
                                                            <span>{{ $registration->nature }}</span>
                                                            <span>•</span>
                                                            <span>{{ $registration->created_at->format('M j, Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                                            @if($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($registration->status === 'approved') bg-green-100 text-green-800
                                                            @elseif($registration->status === 'rejected') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($registration->status) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                @if($registration->rationale)
                                                    <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $registration->rationale }}</p>
                                                @endif

                                                @if($registration->recommended_adviser)
                                                    <p class="text-gray-500 text-xs mt-2">
                                                        <span class="font-medium">Recommended Adviser:</span> {{ $registration->recommended_adviser }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Applications Yet</h3>
                                        <p class="text-gray-500 mb-4">You haven't submitted any club registration applications.</p>
                                        <a href="{{ route('officer.club-registration.create') }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                            Submit Your First Application
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Current Club Section -->
                        @if($currentClub)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-4
                                    @if($currentClub->department === 'SASTE') bg-gradient-to-r from-blue-800 to-blue-900
                                    @elseif($currentClub->department === 'SBAHM') bg-gradient-to-r from-green-800 to-green-900
                                    @elseif($currentClub->department === 'SNAHS') bg-gradient-to-r from-red-800 to-red-900
                                    @elseif($currentClub->department === 'SITE') bg-gradient-to-r from-purple-800 to-purple-900
                                    @elseif($currentClub->department === 'BEU') bg-gradient-to-r from-yellow-700 to-yellow-800
                                    @elseif(in_array($currentClub->department, ['SOM', 'GRADUATE SCHOOL'])) bg-gradient-to-r from-slate-700 to-slate-800
                                    @else bg-gradient-to-r from-slate-700 to-slate-800
                                    @endif">
                                    <h2 class="text-xl font-bold text-white">My Current Club</h2>
                                    <p class="text-white opacity-90 mt-1">{{ $currentClub->name }}</p>
                                </div>

                                <div class="p-6">
                                    <div class="grid grid-cols-2 gap-6 mb-6">
                                        <div class="text-center">
                                            <div class="text-3xl font-bold
                                                @if($currentClub->department === 'SASTE') text-blue-700
                                                @elseif($currentClub->department === 'SBAHM') text-green-700
                                                @elseif($currentClub->department === 'SNAHS') text-red-700
                                                @elseif($currentClub->department === 'SITE') text-purple-700
                                                @elseif($currentClub->department === 'BEU') text-yellow-700
                                                @elseif(in_array($currentClub->department, ['SOM', 'GRADUATE SCHOOL'])) text-slate-700
                                                @else text-slate-700
                                                @endif">{{ $currentClub->member_count }}</div>
                                            <div class="text-sm text-gray-500">Members</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-3xl font-bold
                                                @if($currentClub->department === 'SASTE') text-blue-800
                                                @elseif($currentClub->department === 'SBAHM') text-green-800
                                                @elseif($currentClub->department === 'SNAHS') text-red-800
                                                @elseif($currentClub->department === 'SITE') text-purple-800
                                                @elseif($currentClub->department === 'BEU') text-yellow-800
                                                @elseif(in_array($currentClub->department, ['SOM', 'GRADUATE SCHOOL'])) text-slate-800
                                                @else text-slate-800
                                                @endif">{{ $currentClub->officers->count() }}</div>
                                            <div class="text-sm text-gray-500">Officers</div>
                                        </div>
                                    </div>

                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Department:</span>
                                            <span class="font-medium">{{ $currentClub->department }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Type:</span>
                                            <span class="font-medium">{{ $currentClub->club_type }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Status:</span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $currentClub->status_badge }}">
                                                {{ ucfirst($currentClub->status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Registered:</span>
                                            <span class="font-medium">{{ $currentClub->date_registered ? $currentClub->date_registered->format('M j, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Adviser:</span>
                                            <span class="font-medium">{{ $currentClub->adviser_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Renewals Section (Placeholder) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Club Renewals</h2>
                                <p class="text-gray-600 mt-1">Manage your club renewal applications</p>
                            </div>

                            <div class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Renewal System Coming Soon</h3>
                                    <p class="text-gray-500">Club renewal functionality will be available here.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Profile Section -->
                    <div class="space-y-8">

                        <!-- Profile Information -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ editingProfile: false, editingPassword: false }">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Profile Settings</h2>
                                <p class="text-gray-600 mt-1">Manage your account information</p>
                            </div>

                            <div class="p-6">

                                <!-- Success/Error Messages -->
                                @if(session('success'))
                                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                        <ul class="list-disc list-inside text-sm">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Profile Information Display -->
                                <div x-show="!editingProfile" class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Name</label>
                                        <p class="text-gray-900 font-medium">{{ $officer->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email</label>
                                        <p class="text-gray-900">{{ $officer->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Department</label>
                                        <p class="text-gray-900">{{ $officer->department }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Year Level</label>
                                        <p class="text-gray-900">{{ $officer->year_level }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Club Status</label>
                                        <p class="text-gray-900">{{ $officer->club_status }}</p>
                                    </div>

                                    <div class="flex space-x-3 pt-4">
                                        <button @click="editingProfile = true"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Edit Profile
                                        </button>
                                        <button @click="editingPassword = true"
                                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Change Password
                                        </button>
                                    </div>
                                </div>

                                <!-- Profile Edit Form -->
                                <form x-show="editingProfile" method="POST" action="{{ route('officer.update-profile') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" name="name" value="{{ $officer->name }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" value="{{ $officer->email }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                        <select name="department" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="SASTE" {{ $officer->department === 'SASTE' ? 'selected' : '' }}>SASTE</option>
                                            <option value="SBAHM" {{ $officer->department === 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                                            <option value="SNAHS" {{ $officer->department === 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                                            <option value="SITE" {{ $officer->department === 'SITE' ? 'selected' : '' }}>SITE</option>
                                            <option value="BEU" {{ $officer->department === 'BEU' ? 'selected' : '' }}>BEU</option>
                                            <option value="SOM" {{ $officer->department === 'SOM' ? 'selected' : '' }}>SOM</option>
                                            <option value="GRADUATE SCHOOL" {{ $officer->department === 'GRADUATE SCHOOL' ? 'selected' : '' }}>Graduate School</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                        <select name="year_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="1st Year" {{ $officer->year_level === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                            <option value="2nd Year" {{ $officer->year_level === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                            <option value="3rd Year" {{ $officer->year_level === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                            <option value="4th Year" {{ $officer->year_level === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                            <option value="5th Year" {{ $officer->year_level === '5th Year' ? 'selected' : '' }}>5th Year</option>
                                            <option value="Graduate" {{ $officer->year_level === 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                        </select>
                                    </div>

                                    <div class="flex space-x-3 pt-4">
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Save Changes
                                        </button>
                                        <button type="button" @click="editingProfile = false"
                                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>

                                <!-- Password Change Form -->
                                <form x-show="editingPassword" method="POST" action="{{ route('officer.update-password') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                        <input type="password" name="current_password" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <input type="password" name="new_password" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <div class="flex space-x-3 pt-4">
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Update Password
                                        </button>
                                        <button type="button" @click="editingPassword = false"
                                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
                                <p class="text-gray-600 mt-1">Common tasks and shortcuts</p>
                            </div>

                            <div class="p-6 space-y-3">
                                <a href="{{ route('officer.club-registration.create') }}"
                                   class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center">
                                    Submit New Application
                                </a>
                                <a href="{{ route('officer.club-registration.index') }}"
                                   class="block w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center">
                                    View All Applications
                                </a>
                                <div class="block w-full bg-gray-50 text-gray-400 px-4 py-3 rounded-lg text-sm font-medium text-center cursor-not-allowed">
                                    Club Renewal (Coming Soon)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
