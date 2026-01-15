<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Session Management Script -->
    <script>
        // Session management
        let sessionTimeout;
        let warningTimeout;
        let warningShown = false;

        // Session timeout (2 hours = 7200000ms for admins, 30 minutes for others)
        const SESSION_TIMEOUT = @if(session('user_type') === 'admin') 7200000 @else 1800000 @endif;
        const WARNING_TIME = 900000; // Show warning 15 minutes before timeout

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
            const userChoice = confirm('Your session will expire in 5 minutes. Click OK to stay logged in or Cancel to logout.');

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
            // Simplified - no server call needed to prevent session issues
            console.log('Navigation detected');
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
                // Only reload if user is clearly not authenticated
                if (window.location.pathname === '/login' || document.body.classList.contains('login-page')) {
                    window.location.reload();
                }
                // Otherwise, let the user stay on the page without forced reload
            }
        });

        // Handle page refresh detection (but don't auto-logout)
        let isRefresh = false;
        window.addEventListener('beforeunload', function(e) {
            isRefresh = true;
        });

        // Note: Removed automatic logout on page refresh to prevent unwanted logouts
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->


            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Include confirmation modal component -->
    <x-confirmation-modal />
</body>
</html>

