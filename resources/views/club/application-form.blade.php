<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply to {{ $club->name }} - Club Application</title>
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
                    <h1 class="text-2xl font-bold text-white">Club Application</h1>
                    <p class="text-green-200 mt-1">Apply to join {{ $club->name }}</p>
                </div>
                <a href="{{ route('club.login') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Back to Clubs
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Club Info Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">{{ $club->name }}</h2>
                <p class="text-gray-600 mt-2">{{ $club->department }} • {{ $club->club_type }}</p>
                @if($club->description)
                    <p class="text-sm text-gray-600 mt-3 max-w-2xl mx-auto">{{ $club->description }}</p>
                @endif
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
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

        <!-- Application Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Application Information</h2>
                <p class="text-gray-600 mt-1">Please provide your information to apply for membership in this club.</p>
            </div>

            <form action="{{ route('club.club-application.submit', $club) }}" method="POST" class="p-6 space-y-6" autocomplete="off">
                @csrf

                <!-- First Name and Last Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}" 
                               required
                               autocomplete="off" 
                               autocapitalize="words"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}" 
                               required
                               autocomplete="off" 
                               autocapitalize="words"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Suffix and Age -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="suffix" class="block text-sm font-medium text-gray-700 mb-2">Suffix (Optional)</label>
                        <select id="suffix" 
                                name="suffix"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Select Suffix</option>
                            <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                            <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                            <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                            <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                            <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                            <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                        </select>
                    </div>

                    <div id="ageField">
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age *</label>
                        <input type="number" 
                               id="age" 
                               name="age" 
                               value="{{ old('age') }}"
                               min="1"
                               max="150"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('age') border-red-500 @enderror">
                        @error('age')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Gender and Phone Number -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <select id="gender" 
                                name="gender" 
                                required
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('gender') border-red-500 @enderror">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" 
                               id="phone_number" 
                               name="phone_number" 
                               value="{{ old('phone_number') }}" 
                               required
                               placeholder="09XXXXXXXXX"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Philippine mobile number (e.g., 09171234567)</p>
                    </div>
                </div>

                <!-- Position Applying For (full width) -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position Applying For *</label>
                    <select id="position" 
                            name="position" 
                            required
                            autocomplete="off"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('position') border-red-500 @enderror">
                        <option value="">Select a position</option>
                        <option value="member" {{ old('position') == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="officer" {{ old('position') == 'officer' ? 'selected' : '' }}>Officer</option>
                        <option value="adviser" {{ old('position') == 'adviser' ? 'selected' : '' }}>Adviser</option>
                    </select>
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Select the role you wish to apply for</p>
                </div>

                <!-- Student ID / Professor ID and Email Address -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id_field" id="idLabel" class="block text-sm font-medium text-gray-700 mb-2">Student ID *</label>
                        <input type="text" 
                               id="student_id" 
                               name="student_id" 
                               value="{{ old('student_id') }}" 
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('student_id') border-red-500 @enderror">
                        <input type="text" 
                               id="professor_id" 
                               name="professor_id" 
                               value="{{ old('professor_id') }}" 
                               autocomplete="off"
                               style="display: none;"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('professor_id') border-red-500 @enderror">
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('professor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Gmail Address *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="your.email@school.edu"
                               required
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Course/Program and Year Level / Department Office -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div id="courseField">
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Course/Program *</label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department') }}" 
                               placeholder="e.g., BS Computer Science"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('department') border-red-500 @enderror">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="departmentOfficeField" style="display: none;">
                        <label for="department_office" class="block text-sm font-medium text-gray-700 mb-2">Department Office *</label>
                        <select id="department_office" 
                                name="department_office"
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('department_office') border-red-500 @enderror">
                            <option value="">Select Department</option>
                            <option value="SASTE" {{ old('department_office') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                            <option value="SNAHS" {{ old('department_office') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                            <option value="SITE" {{ old('department_office') == 'SITE' ? 'selected' : '' }}>SITE</option>
                            <option value="SBAHM" {{ old('department_office') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                            <option value="BEU" {{ old('department_office') == 'BEU' ? 'selected' : '' }}>BEU</option>
                            <option value="SOM" {{ old('department_office') == 'SOM' ? 'selected' : '' }}>SOM</option>
                            <option value="GRADUATE SCHOOL" {{ old('department_office') == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                        </select>
                        @error('department_office')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="yearLevelField">
                        <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level *</label>
                        <select id="year_level" 
                                name="year_level"
                                autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('year_level') border-red-500 @enderror">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                        @error('year_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Preferred Password *</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               minlength="8"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               minlength="8"
                               autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">Re-enter your password</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('club.login') }}" 
                       class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-800 border border-gray-300 hover:border-gray-400 rounded-lg transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 font-medium">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Position change handler
        document.getElementById('position').addEventListener('change', function() {
            const position = this.value;
            const ageField = document.getElementById('ageField');
            const ageInput = document.getElementById('age');
            const studentIdInput = document.getElementById('student_id');
            const professorIdInput = document.getElementById('professor_id');
            const idLabel = document.getElementById('idLabel');
            const courseField = document.getElementById('courseField');
            const departmentInput = document.getElementById('department');
            const departmentOfficeField = document.getElementById('departmentOfficeField');
            const departmentOfficeInput = document.getElementById('department_office');
            const yearLevelField = document.getElementById('yearLevelField');
            const yearLevelInput = document.getElementById('year_level');

            if (position === 'adviser') {
                // Hide age field
                ageField.style.display = 'none';
                ageInput.required = false;
                ageInput.value = '';

                // Switch to Professor ID
                idLabel.textContent = 'Professor ID *';
                studentIdInput.style.display = 'none';
                studentIdInput.required = false;
                studentIdInput.value = '';
                professorIdInput.style.display = 'block';
                professorIdInput.required = true;

                // Hide course, show department office
                courseField.style.display = 'none';
                departmentInput.required = false;
                departmentInput.value = '';
                departmentOfficeField.style.display = 'block';
                departmentOfficeInput.required = true;

                // Hide year level
                yearLevelField.style.display = 'none';
                yearLevelInput.required = false;
                yearLevelInput.value = '';
            } else {
                // Show age field
                ageField.style.display = 'block';
                ageInput.required = true;

                // Switch to Student ID
                idLabel.textContent = 'Student ID *';
                studentIdInput.style.display = 'block';
                studentIdInput.required = true;
                professorIdInput.style.display = 'none';
                professorIdInput.required = false;
                professorIdInput.value = '';

                // Show course, hide department office
                courseField.style.display = 'block';
                departmentInput.required = true;
                departmentOfficeField.style.display = 'none';
                departmentOfficeInput.required = false;
                departmentOfficeInput.value = '';

                // Show year level
                yearLevelField.style.display = 'block';
                yearLevelInput.required = true;
            }
        });

        // Password confirmation validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (confirmPassword && password !== confirmPassword) {
                document.getElementById('password_confirmation').classList.add('border-red-500');
            } else {
                document.getElementById('password_confirmation').classList.remove('border-red-500');
            }
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    </script>
</body>
</html>
