<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Club Registration - Officer Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
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
        <header class="bg-blue-600 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Club Registration</h1>
                    <p class="text-blue-100">Manage your club registration applications</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('officer.dashboard') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm">
                        Back to Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Your Applications</h2>
                            <p class="text-gray-600">View and manage your club registration requests</p>
                        </div>
                        <a href="{{ route('officer.club-registration.create') }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            New Application
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($registrations as $registration)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $registration->club_name }}</h3>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <span>{{ $registration->department }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $registration->nature }}</span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Submitted {{ $registration->created_at->format('M j, Y') }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Adviser: {{ $registration->recommended_adviser }}
                                        </div>

                                        @if($registration->status === 'rejected' && $registration->rejection_reason)
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-red-800 font-medium">Rejection Reason:</p>
                                            <p class="text-sm text-red-700">{{ $registration->rejection_reason }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="ml-6">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            {{ $registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $registration->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first club registration application.</p>
                                <div class="mt-6">
                                    <a href="{{ route('officer.club-registration.create') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        New Application
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

