<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Application for Recognition - Officer Dashboard</title>
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
        <header class="bg-green-800 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-white">APPLICATION FOR RECOGNITION OF NEW CLUB/ORGANIZATION</h1>
                    <p class="text-green-200">Submit your application for club/organization recognition</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('officer.club-registration.index') }}" class="bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                        Back to Applications
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="max-w-4xl mx-auto">
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('officer.club-registration.store') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- Application Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Application Information</h2>

                        <div class="space-y-6">
                            <div>
                                <label for="club_name" class="block text-sm font-medium text-gray-700 mb-2">1. Name of Club/Organization *</label>
                                <input type="text" id="club_name" name="club_name" value="{{ old('club_name') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">2. The School Department *</label>
                                <select id="department" name="department" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Department</option>
                                    <option value="SASTE" {{ old('department') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                                    <option value="SNAHS" {{ old('department') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                                    <option value="SITE" {{ old('department') == 'SITE' ? 'selected' : '' }}>SITE</option>
                                    <option value="SBAHM" {{ old('department') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                                    <option value="BEU" {{ old('department') == 'BEU' ? 'selected' : '' }}>BEU</option>
                                    <option value="SOM" {{ old('department') == 'SOM' ? 'selected' : '' }}>SOM</option>
                                    <option value="GRADUATE SCHOOL" {{ old('department') == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                                </select>
                            </div>

                            <div>
                                <label for="nature" class="block text-sm font-medium text-gray-700 mb-2">3. Nature of Club/Organizations *</label>
                                <select id="nature" name="nature" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Nature</option>
                                    <option value="Academic" {{ old('nature') == 'Academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="Interest" {{ old('nature') == 'Interest' ? 'selected' : '' }}>Interest</option>
                                </select>
                            </div>

                            <div>
                                <label for="rationale" class="block text-sm font-medium text-gray-700 mb-2">4. Brief Rationale for organizing such Club/Organization *</label>
                                <textarea id="rationale" name="rationale" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Please provide a brief explanation of why this club/organization should be established...">{{ old('rationale') }}</textarea>
                            </div>

                            <div>
                                <label for="recommended_adviser" class="block text-sm font-medium text-gray-700 mb-2">5. Recommended Faculty Adviser *</label>
                                <input type="text" id="recommended_adviser" name="recommended_adviser" value="{{ old('recommended_adviser') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Full name of the recommended faculty adviser">
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Required Documents</h2>

                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-700 mb-3">Please include the following in the application:</p>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 ml-4">
                                <li>Constitution and By-Laws duly signed/ratified by the forming members</li>
                                <li>List of officers duly certified by the recommended adviser and endorsed by the dean</li>
                                <li>Proposed activities/action plan for the present academic year signed by the chair/president, recommended adviser and endorsed by dean</li>
                                <li>Budget proposal signed by club/organization treasurer, chair/president, and recommended adviser</li>
                            </ol>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                                <p class="text-sm text-blue-800 font-medium">
                                    <strong>NOTE:</strong> All attached documents should be in Word or PDF format.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="constitution_file" class="block text-sm font-medium text-gray-700 mb-2">Constitution and By-Laws *</label>
                                <input type="file" id="constitution_file" name="constitution_file" required
                                    accept=".pdf,.doc,.docx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>

                            <div>
                                <label for="officers_list_file" class="block text-sm font-medium text-gray-700 mb-2">List of Officers *</label>
                                <input type="file" id="officers_list_file" name="officers_list_file" required
                                    accept=".pdf,.doc,.docx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>

                            <div>
                                <label for="activities_plan_file" class="block text-sm font-medium text-gray-700 mb-2">Activities/Action Plan *</label>
                                <input type="file" id="activities_plan_file" name="activities_plan_file" required
                                    accept=".pdf,.doc,.docx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>

                            <div>
                                <label for="budget_proposal_file" class="block text-sm font-medium text-gray-700 mb-2">Budget Proposal *</label>
                                <input type="file" id="budget_proposal_file" name="budget_proposal_file" required
                                    accept=".pdf,.doc,.docx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>
                        </div>
                    </div>

                    <!-- Submission Date -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Submission Information</h2>
                        <div>
                            <label for="submission_date" class="block text-sm font-medium text-gray-700 mb-2">Date of Submission in the OSA *</label>
                            <input type="date" id="submission_date" name="submission_date" value="{{ old('submission_date', now()->format('Y-m-d')) }}" required
                                min="1900-01-01" max="9999-12-31"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="text-xs text-gray-500 mt-1">Format: MM/DD/YYYY</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('officer.club-registration.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                            Submit Application
                        </button>
                    </div>
                </form>

                <!-- N.B. Section -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4">N.B.</h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-yellow-700">
                        <li>To be submitted not later than thirty (30) calendar days before the opening of class of the first semester of the academic year.</li>
                        <li>Submit copies of this form to each of the following after fully signed: OSA, Dean, and the Club/Organization Adviser</li>
                    </ol>
                </div>
            </div>
        </main>
    </div>
</body>
</html>





