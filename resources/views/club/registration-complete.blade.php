<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration Complete - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Registration Complete</h1>
                    <p class="text-green-200 mt-1">Your club registration has been submitted successfully</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Registration Submitted Successfully!</h3>
                    <p class="text-green-700 mt-1">Your club "{{ $credentials['club_name'] }}" registration has been submitted for admin approval.</p>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1 - Complete -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Officer Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-green-600 rounded"></div>
                    
                    <!-- Step 2 - Complete -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Club Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-orange-400 rounded"></div>
                    
                    <!-- Step 3 - Pending -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-400 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-orange-600">Admin Approval</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Credentials -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-green-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900">Your Login Credentials</h2>
                </div>
                <p class="text-gray-600 mt-1">Save these credentials safely - they will be used to track your application status</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               value="{{ $credentials['email'] }}" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 font-mono text-sm">
                        <button onclick="copyToClipboard('{{ $credentials['email'] }}')" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            Copy
                        </button>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 relative">
                            <input type="password" 
                                   id="password-field"
                                   value="{{ $credentials['password'] }}" 
                                   readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 font-mono text-sm pr-10">
                            <button type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="eye-open" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <button onclick="copyToClipboard('{{ $credentials['password'] }}')" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            Copy
                        </button>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-amber-800">Important Information</h4>
                            <ul class="mt-2 text-sm text-amber-700 list-disc list-inside space-y-1">
                                <li>Save these credentials in a secure location</li>
                                <li>You will need them to check your application status</li>
                                <li>Your application is now under admin review</li>
                                <li>You will be notified via the registration tracker once approved</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Download Credentials Button -->
                <div class="text-center">
                    <button onclick="downloadCredentials()" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Credentials
                    </button>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">What's Next?</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Application Review</h3>
                            <p class="text-gray-600 text-sm">The administration will review your club registration application.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Check Status</h3>
                            <p class="text-gray-600 text-sm">Use the registration tracker to monitor your application status.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Approval & Access</h3>
                            <p class="text-gray-600 text-sm">Once approved, you can log in to access your club dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4 mt-8">
            <a href="{{ route('club.registration-tracker') }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Check Application Status
            </a>
            <a href="{{ route('club.login') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Go to Login Page
            </a>
        </div>
    </main>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password-field');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                showNotificationModal('Copy Failed', 'Failed to copy to clipboard. Please try again.', 'error');
            });
        }

        function downloadCredentials() {
            const credentials = {
                email: '{{ $credentials['email'] }}',
                password: '{{ $credentials['password'] }}',
                club: '{{ $credentials['club_name'] }}',
                registration_date: new Date().toLocaleDateString()
            };

            const content = `Club Registration Credentials
            
Club Name: ${credentials.club}
Email: ${credentials.email}
Password: ${credentials.password}
Registration Date: ${credentials.registration_date}

Important: Keep these credentials safe. You will need them to track your application status and log in once approved.

Next Steps:
1. Use the registration tracker to monitor your application status
2. Wait for admin approval
3. Once approved, log in to access your club dashboard`;

            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${credentials.club.replace(/[^a-z0-9]/gi, '_')}_credentials.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }

        // Auto-close warning after showing credentials
        setTimeout(function() {
            const downloadBtn = document.querySelector('button[onclick="downloadCredentials()"]');
            if (downloadBtn) {
                downloadBtn.classList.add('animate-pulse');
                setTimeout(() => {
                    downloadBtn.classList.remove('animate-pulse');
                }, 3000);
            }
        }, 1000);
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
