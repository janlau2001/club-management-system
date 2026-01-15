<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">My Profile</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('club.member.dashboard') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30 shadow-lg">
                        Back to Dashboard
                    </a>
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
                                <p class="text-xs opacity-75">Member</p>
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
                                <p class="text-sm text-gray-500">{{ $clubUser->email }}</p>
                                <p class="text-xs text-gray-400">{{ $clubUser->year_level }} • {{ $clubUser->department }}</p>
                            </div>

                            <!-- Menu Items -->
                            <a href="{{ route('club.member.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 bg-gray-50">
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

        <main class="p-6">
            <div class="max-w-4xl mx-auto space-y-8">
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Profile Overview Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-green-50 px-6 py-8 border-b border-gray-200">
                        <div class="flex items-center space-x-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $clubUser->name }}</h2>
                                <p class="text-lg text-gray-600">Member</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $club->name }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $clubUser->department }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-500">Student ID</div>
                                <div class="text-lg font-semibold text-gray-900 mt-1">{{ $clubUser->student_id }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-500">Year Level</div>
                                <div class="text-lg font-semibold text-gray-900 mt-1">{{ $clubUser->year_level }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-500">Course</div>
                                <div class="text-lg font-semibold text-gray-900 mt-1">{{ $clubUser->course ?? 'N/A' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-500">Member Since</div>
                                <div class="text-lg font-semibold text-gray-900 mt-1">{{ $clubUser->created_at->format('M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tabs -->
                <div x-data="{ activeTab: 'profile' }" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button @click="activeTab = 'profile'" 
                                    :class="activeTab === 'profile' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Edit Profile
                            </button>
                            <button @click="activeTab = 'email'" 
                                    :class="activeTab === 'email' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Change Email
                            </button>
                            <button @click="activeTab = 'password'" 
                                    :class="activeTab === 'password' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Change Password
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Contents -->
                    <div class="p-6">
                        <!-- Edit Profile Tab -->
                        <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                <form method="POST" action="{{ route('club.member.update-profile') }}" class="space-y-6">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $clubUser->name) }}" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        </div>
                                        
                                        <div>
                                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                                            <select name="year_level" id="year_level" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <option value="">Select Year Level</option>
                                                <option value="1st Year" {{ old('year_level', $clubUser->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                <option value="2nd Year" {{ old('year_level', $clubUser->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                <option value="3rd Year" {{ old('year_level', $clubUser->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                <option value="4th Year" {{ old('year_level', $clubUser->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                                <option value="Graduate" {{ old('year_level', $clubUser->year_level) == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course/Program</label>
                                        <input type="text" name="course" id="course" value="{{ old('course', $clubUser->course) }}" required
                                               placeholder="e.g., BS Information Technology"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input type="tel" 
                                               name="phone" 
                                               id="phone_edit" 
                                               value="{{ old('phone', $clubUser->phone ? preg_replace('/\D/', '', $clubUser->phone) : '') }}" 
                                               required
                                               maxlength="11"
                                               pattern="[0-9]{11}"
                                               placeholder="09XXXXXXXXX"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               oninput="formatPhoneNumber(this)">
                                        <p class="text-sm text-gray-500 mt-1">11 digits (e.g., 09171234567)</p>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change Email Tab -->
                        <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Email Address</h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-yellow-800">Important Note</h4>
                                            <p class="text-sm text-yellow-700 mt-1">Changing your email will require password verification for security purposes.</p>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('club.member.update-email') }}" class="space-y-6">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div>
                                        <label for="current_email" class="block text-sm font-medium text-gray-700 mb-2">Current Email</label>
                                        <input type="email" id="current_email" value="{{ $clubUser->email }}" disabled
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                                    </div>
                                    
                                    <div>
                                        <label for="new_email" class="block text-sm font-medium text-gray-700 mb-2">New Email Address</label>
                                        <input type="email" name="new_email" id="new_email" value="{{ old('new_email') }}" required
                                               placeholder="Enter your new email address"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="password_for_email" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                        <input type="password" name="current_password" id="password_for_email" required
                                               placeholder="Enter your current password to confirm"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Update Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password Tab -->
                        <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="max-w-2xl">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-medium text-blue-800">Password Requirements</h4>
                                            <p class="text-sm text-blue-700 mt-1">Password should be at least 8 characters long for security.</p>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('club.member.update-password') }}" class="space-y-6">
                                    @csrf
                                    
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                        <input type="password" name="current_password" id="current_password" required 
                                               placeholder="Enter your current password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>

                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="new_password" id="new_password" required 
                                               placeholder="Enter your new password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>

                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required 
                                               placeholder="Confirm your new password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function formatPhoneNumber(input) {
            // Remove all non-digit characters
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 11 digits
            value = value.substring(0, 11);
            
            // Update input value
            input.value = value;
            
            // Basic validation for Philippine numbers (starts with 09)
            if (value.length > 0 && !value.startsWith('09') && value.length > 1) {
                let phoneError = input.parentNode.querySelector('.phone-error');
                if (!phoneError) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'text-xs text-red-500 mt-1 phone-error';
                    errorDiv.textContent = 'Phone number should start with 09';
                    input.parentNode.appendChild(errorDiv);
                }
                input.classList.add('border-red-500');
            } else {
                let phoneError = input.parentNode.querySelector('.phone-error');
                if (phoneError) {
                    phoneError.remove();
                }
                input.classList.remove('border-red-500');
            }
        }
    </script>
</body>
</html>
                    
                    <!-- Profile Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                            <p class="text-gray-600 mt-1">Your basic information and club details</p>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $clubUser->name }}</h3>
                                    <p class="text-sm text-gray-500">Member</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->email }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->student_id }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->year_level }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->department }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->course ?? 'Not specified' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->formatted_phone ?? 'Not specified' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Club</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $club->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $clubUser->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Edit Profile</h2>
                            <p class="text-gray-600 mt-1">Update your personal information</p>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('club.member.update-profile') }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $clubUser->name) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                    <select name="year_level" id="year_level" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', $clubUser->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $clubUser->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $clubUser->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $clubUser->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="Graduate" {{ old('year_level', $clubUser->year_level) == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course/Program</label>
                                    <input type="text" name="course" id="course" value="{{ old('course', $clubUser->course) }}" required
                                           placeholder="e.g., BS Computer Science"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone_edit" 
                                           value="{{ old('phone', $clubUser->phone ? preg_replace('/\D/', '', $clubUser->phone) : '') }}" 
                                           required
                                           maxlength="11"
                                           pattern="[0-9]{11}"
                                           placeholder="09XXXXXXXXX"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           oninput="formatPhoneNumber(this)">
                                    <p class="text-xs text-gray-500 mt-1">11 digits (e.g., 09171234567)</p>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Change Password</h2>
                            <p class="text-gray-600 mt-1">Update your account password</p>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('club.member.update-password') }}" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                </div>

                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="new_password" id="new_password" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                </div>

                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                </div>

                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function formatPhoneNumber(input) {
            // Remove all non-digit characters
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 11 digits
            value = value.substring(0, 11);
            
            // Update input value
            input.value = value;
            
            // Basic validation for Philippine numbers (starts with 09)
            if (value.length > 0 && !value.startsWith('09') && value.length > 1) {
                let phoneError = input.parentNode.querySelector('.phone-error');
                if (!phoneError) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'text-xs text-red-500 mt-1 phone-error';
                    errorDiv.textContent = 'Phone number should start with 09';
                    input.parentNode.appendChild(errorDiv);
                }
                input.classList.add('border-red-500');
            } else {
                let phoneError = input.parentNode.querySelector('.phone-error');
                if (phoneError) {
                    phoneError.remove();
                }
                input.classList.remove('border-red-500');
            }
        }
    </script>
</body>
</html>
