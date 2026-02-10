<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Information - Club Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4">
    <!-- Header -->
    <header class="bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg mb-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Register Your Club</h1>
                    <p class="text-green-200 mt-1">Step 2 of 3: Personal Information</p>
                </div>
                <a href="{{ route('club.login') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <p class="text-sm text-green-600 mb-4">
                <i class="fas fa-check-circle mr-1"></i> Email Verified: {{ $officer->email }}
            </p>
            <h2 class="text-2xl font-semibold text-gray-900">Personal Information</h2>
            <p class="text-sm text-gray-600 mt-2">Please provide your personal details to continue</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Please correct the following errors:</p>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('club.officer-registration.store') }}">
            @csrf
            <input type="hidden" name="officer_id" value="{{ $officer->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- First Name -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-user mr-1"></i> First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-user mr-1"></i> Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                </div>

                <!-- Suffix -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-user mr-1"></i> Suffix
                    </label>
                    <select name="suffix"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <option value="">None</option>
                        <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                        <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                        <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                        <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                        <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                        <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-phone mr-1"></i> Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="09** *** ****"
                        maxlength="13"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                    <p class="text-xs text-gray-500 mt-1">Format: 09** *** ****</p>
                </div>

                <!-- Student ID -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-id-card mr-1"></i> Student ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="student_id" value="{{ old('student_id') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                </div>

                <!-- Year Level -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-graduation-cap mr-1"></i> Year Level <span class="text-red-500">*</span>
                    </label>
                    <select name="year_level"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-building mr-1"></i> Department <span class="text-red-500">*</span>
                    </label>
                    <select name="department"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                        <option value="">Select Department</option>
                        <option value="SASTE" {{ old('department') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                        <option value="SBAHM" {{ old('department') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                        <option value="SNAHS" {{ old('department') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                        <option value="SITE" {{ old('department') == 'SITE' ? 'selected' : '' }}>SITE</option>
                        <option value="BEU" {{ old('department') == 'BEU' ? 'selected' : '' }}>BEU</option>
                        <option value="SOM" {{ old('department') == 'SOM' ? 'selected' : '' }}>SOM</option>
                        <option value="GRADUATE SCHOOL" {{ old('department') == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                    </select>
                </div>
            </div>

            <!-- Course -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-book mr-1"></i> Course <span class="text-red-500">*</span>
                </label>
                <input type="text" name="course" value="{{ old('course') }}"
                    placeholder="e.g., BS Computer Science"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
            </div>

            @if(isset($needsPassword) && $needsPassword)
            <!-- Password Section (for Google OAuth users only) -->
            <div class="mb-6 p-5 bg-blue-50 border-2 border-blue-200 rounded-xl">
                <div class="flex items-start mb-4">
                    <i class="fas fa-lock text-blue-600 mr-2 mt-1"></i>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Set Your Password</h3>
                        <p class="text-xs text-blue-700">Create a password to access the registration tracker and login to your club account.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-key mr-1"></i> Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            required minlength="8">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-key mr-1"></i> Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            required minlength="8">
                        <p class="text-xs text-gray-500 mt-1" id="password-match-msg"></p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    <i class="fas fa-crown text-yellow-500 mr-1"></i>
                    <strong>Note:</strong> As the person registering this club, you will automatically become the <strong>President</strong> of the organization.
                </p>
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex gap-4">
                <button type="button" onclick="cancelRegistration()" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 border border-red-300 hover:border-red-400 font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#29553c] to-[#031a0a] hover:from-[#1e3d2c] hover:to-[#000000] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-right mr-2"></i> Continue to Club Registration
                </button>
            </div>
        </form>
    </div>

    <!-- Auto Cleanup Script -->
    <script>
        function cancelRegistration() {
            // Cleanup the incomplete registration
            const url = '{{ route("club.registration.cleanup", $officer->id) }}';
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(() => {
                // Redirect to login after cleanup
                window.location.href = '{{ route("club.login") }}';
            }).catch(() => {
                // Even if cleanup fails, still redirect
                window.location.href = '{{ route("club.login") }}';
            });
        }

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits
            
            // Limit to 11 digits
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // Format: 09** *** ****
            let formatted = '';
            if (value.length > 0) {
                formatted = value.slice(0, 4); // First 4 digits: 09**
                if (value.length > 4) {
                    formatted += ' ' + value.slice(4, 7); // Next 3 digits: ***
                }
                if (value.length > 7) {
                    formatted += ' ' + value.slice(7, 11); // Last 4 digits: ****
                }
            }
            
            e.target.value = formatted;
        });

        // Password validation (only if password fields exist)
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const matchMsg = document.getElementById('password-match-msg');

        if (passwordInput && confirmPasswordInput && matchMsg) {
            function checkPasswordMatch() {
                if (confirmPasswordInput.value === '') {
                    matchMsg.textContent = '';
                    matchMsg.className = 'text-xs text-gray-500 mt-1';
                    return;
                }

                if (passwordInput.value === confirmPasswordInput.value) {
                    matchMsg.textContent = '✓ Passwords match';
                    matchMsg.className = 'text-xs text-green-600 mt-1';
                } else {
                    matchMsg.textContent = '✗ Passwords do not match';
                    matchMsg.className = 'text-xs text-red-600 mt-1';
                }
            }

            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Validate on form submit
            document.querySelector('form').addEventListener('submit', function(e) {
                // Check if passwords match before submitting
                if (passwordInput.value !== confirmPasswordInput.value) {
                    e.preventDefault();
                    matchMsg.textContent = '✗ Passwords do not match';
                    matchMsg.className = 'text-xs text-red-600 mt-1';
                    confirmPasswordInput.focus();
                    return false;
                }
                formSubmitted = true;
            });
        } else {
            // No password fields, just mark as submitted on form submit
            document.querySelector('form').addEventListener('submit', function() {
                formSubmitted = true;
            });
        }

        // Track if form was submitted successfully
        let formSubmitted = false;

        // Cleanup on page unload (close/navigate away)
        window.addEventListener('beforeunload', function(e) {
            if (!formSubmitted) {
                // Use sendBeacon for reliable cleanup during unload
                const url = '{{ route("club.registration.cleanup", $officer->id) }}';
                const data = new FormData();
                data.append('_method', 'DELETE');
                data.append('_token', '{{ csrf_token() }}');
                navigator.sendBeacon(url, data);
            }
        });

        // Also cleanup when page visibility changes (tab switch, minimize)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && !formSubmitted) {
                // Page is hidden, but don't cleanup immediately
                // Set a timeout to cleanup if user doesn't return
                setTimeout(function() {
                    if (document.hidden && !formSubmitted) {
                        const url = '{{ route("club.registration.cleanup", $officer->id) }}';
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }).catch(() => {}); // Ignore errors
                    }
                }, 300000); // 5 minutes of inactivity
            }
        });
    </script>
