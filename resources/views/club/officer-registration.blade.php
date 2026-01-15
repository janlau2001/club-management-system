<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Officer Registration - Club Management</title>
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
                    <h1 class="text-2xl font-bold text-white">Officer Registration</h1>
                    <p class="text-green-200 mt-1">Step 1: Register as a Club Officer</p>
                </div>
                <a href="{{ route('club.login') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Back to Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1 - Active -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Officer Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-gray-300 rounded"></div>
                    
                    <!-- Step 2 - Inactive -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Club Registration</span>
                    </div>
                    
                    <!-- Connector -->
                    <div class="w-16 h-1 bg-gray-300 rounded"></div>
                    
                    <!-- Step 3 - Inactive -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Admin Approval</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Officer Information</h2>
                <p class="text-gray-600 mt-1">Please provide your information as the club officer who will be responsible for the registration process.</p>
            </div>

            <form method="POST" action="{{ route('club.officer-registration.store') }}" class="p-6 space-y-6" autocomplete="off">
                @csrf
                
                <!-- Hidden fields for edit mode -->
                @if(isset($editMode) && $editMode)
                    <input type="hidden" name="edit_mode" value="1">
                    <input type="hidden" name="officer_id" value="{{ $officerId }}">
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', request('first_name')) }}" required
                               autocomplete="off" autocapitalize="words"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', request('last_name')) }}" required
                               autocomplete="off" autocapitalize="words"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="suffix" class="block text-sm font-medium text-gray-700 mb-2">Suffix (Optional)</label>
                        <select id="suffix" name="suffix"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Suffix</option>
                            <option value="Jr." {{ old('suffix', request('suffix')) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                            <option value="Sr." {{ old('suffix', request('suffix')) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                            <option value="II" {{ old('suffix', request('suffix')) == 'II' ? 'selected' : '' }}>II</option>
                            <option value="III" {{ old('suffix', request('suffix')) == 'III' ? 'selected' : '' }}>III</option>
                            <option value="IV" {{ old('suffix', request('suffix')) == 'IV' ? 'selected' : '' }}>IV</option>
                            <option value="V" {{ old('suffix', request('suffix')) == 'V' ? 'selected' : '' }}>V</option>
                        </select>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" readonly
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                               placeholder="Email will be auto-generated after registration">
                        <p class="text-xs text-gray-500 mt-1">An email address will be automatically provided</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', request('phone')) }}" 
                               required
                               maxlength="11"
                               pattern="[0-9]{11}"
                               placeholder="09XXXXXXXXX"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               oninput="formatPhoneNumber(this)">
                        <p class="text-xs text-gray-500 mt-1">11 digits (e.g., 09171234567)</p>
                    </div>

                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID *</label>
                        <input type="text" id="student_id" name="student_id" value="{{ old('student_id', request('student_id')) }}" required
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level *</label>
                        <select id="year_level" name="year_level" required
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level', request('year_level')) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level', request('year_level')) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level', request('year_level')) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level', request('year_level')) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                            <option value="Graduate" {{ old('year_level', request('year_level')) == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                        </select>
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course/Program *</label>
                        <input type="text" id="course" name="course" value="{{ old('course', request('course')) }}" required
                               placeholder="e.g., BS Computer Science"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select id="department" name="department" required
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Department</option>
                            <option value="SASTE" {{ old('department', request('department')) == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                            <option value="SNAHS" {{ old('department', request('department')) == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                            <option value="SITE" {{ old('department', request('department')) == 'SITE' ? 'selected' : '' }}>SITE</option>
                            <option value="SBAHM" {{ old('department', request('department')) == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                            <option value="BEU" {{ old('department', request('department')) == 'BEU' ? 'selected' : '' }}>BEU</option>
                            <option value="SOM" {{ old('department', request('department')) == 'SOM' ? 'selected' : '' }}>SOM</option>
                            <option value="GRADUATE SCHOOL" {{ old('department', request('department')) == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                        </select>
                    </div>
                </div>

                <!-- Officer Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Officer Position</label>
                    <input type="text" id="position" name="position" value="Officer" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    <p class="text-xs text-gray-500 mt-1">Position can be updated in your profile after registration</p>
                </div>

                <!-- Password (Hidden) -->
                <div>
                    <input type="hidden" id="password" name="password" value="">
                    <input type="hidden" id="password_confirmation" name="password_confirmation" value="">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm text-blue-800 font-medium">Login credentials will be provided after completing club registration</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('club.login') }}" 
                       class="px-6 py-2 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 border border-red-300 hover:border-red-400 rounded-lg transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 font-medium">
                        Continue to Club Registration →
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto-generate password on page load
        document.addEventListener('DOMContentLoaded', function() {
            const password = generatePassword();
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;
            
            // Additional autocomplete prevention
            disableAutocomplete();
        });

        function generatePassword() {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            return password;
        }

        function disableAutocomplete() {
            // Get all input and select elements
            const formElements = document.querySelectorAll('input, select, textarea');
            
            formElements.forEach(function(element) {
                // Skip readonly and hidden elements
                if (!element.readOnly && element.type !== 'hidden') {
                    element.setAttribute('autocomplete', 'off');
                    element.setAttribute('autocapitalize', 'off');
                    element.setAttribute('autocorrect', 'off');
                    element.setAttribute('spellcheck', 'false');
                    
                    // Add event listeners to prevent autocomplete
                    element.addEventListener('focus', function() {
                        this.setAttribute('autocomplete', 'new-password');
                    });
                    
                    element.addEventListener('blur', function() {
                        this.setAttribute('autocomplete', 'off');
                    });
                }
            });
            
            // Clear any stored browser form data
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }

        function formatPhoneNumber(input) {
            // Remove all non-digit characters
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 11 digits
            value = value.substring(0, 11);
            
            // Update input value
            input.value = value;
            
            // Basic validation for Philippine numbers (starts with 09)
            if (value.length > 0 && !value.startsWith('09') && value.length > 1) {
                const phoneError = document.getElementById('phone-error');
                if (!phoneError) {
                    const errorDiv = document.createElement('p');
                    errorDiv.id = 'phone-error';
                    errorDiv.className = 'text-xs text-red-500 mt-1';
                    errorDiv.textContent = 'Phone number should start with 09';
                    input.parentNode.appendChild(errorDiv);
                }
                input.classList.add('border-red-500');
            } else {
                const phoneError = document.getElementById('phone-error');
                if (phoneError) {
                    phoneError.remove();
                }
                input.classList.remove('border-red-500');
            }
        }
    </script>
</body>
</html>
