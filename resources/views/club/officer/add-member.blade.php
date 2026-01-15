<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Member/Officer - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Add Member/Officer</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('club.officer.dashboard') }}"
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

        <main class="p-6">
            <div class="max-w-2xl mx-auto">
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Add Member/Officer Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Add New Club Member or Officer</h2>
                        <p class="text-gray-600 mt-1">Register a new person to join {{ $club->name }}</p>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('club.officer.add-member.store') }}" class="space-y-6"
                              x-data="{ 
                                  role: '{{ old('role', 'member') }}',
                                  position: '{{ old('position', '') }}',
                                  init() {
                                      console.log('Alpine initialized with role:', this.role);
                                      this.$watch('role', (newRole) => {
                                          console.log('Role changed to:', newRole);
                                          // Clear position when role changes
                                          this.position = '';
                                      });
                                  }
                              }"
                              x-init="console.log('Form initialized')">
                            @csrf

                            <!-- Personal Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                           placeholder="Enter full name">
                                    @error('name')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" required
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                                           placeholder="Enter email address">
                                    @error('email')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                                    <input type="text" name="student_id" id="student_id" required
                                           value="{{ old('student_id') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('student_id') border-red-500 @enderror"
                                           placeholder="Enter student ID">
                                    @error('student_id')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" id="password" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('password') border-red-500 @enderror"
                                           placeholder="Enter password">
                                    @error('password')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Role Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Role in Club <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-3 gap-4">
                                    <label class="relative">
                                        <input type="radio" name="role" value="member" x-model="role" required
                                               {{ old('role', 'member') === 'member' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-full p-4 text-center border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium">Member</span>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="role" value="officer" x-model="role" required
                                               {{ old('role') === 'officer' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-full p-4 text-center border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                            <span class="font-medium">Officer</span>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="role" value="adviser" x-model="role" required
                                               {{ old('role') === 'adviser' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-full p-4 text-center border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                            </svg>
                                            <span class="font-medium">Adviser</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Officer Position (shown only when officer is selected) -->
                            <div x-show="role === 'officer'" x-transition>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Officer Position <span class="text-red-500">*</span></label>
                                <select name="position" id="position"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                        x-model="position">
                                    <option value="">Select Position</option>
                                    <option value="President" {{ old('position') === 'President' ? 'selected' : '' }}>President</option>
                                    <option value="Vice President" {{ old('position') === 'Vice President' ? 'selected' : '' }}>Vice President</option>
                                    <option value="Secretary" {{ old('position') === 'Secretary' ? 'selected' : '' }}>Secretary</option>
                                    <option value="Treasurer" {{ old('position') === 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                                    <option value="Auditor" {{ old('position') === 'Auditor' ? 'selected' : '' }}>Auditor</option>
                                    <option value="Public Relations Officer" {{ old('position') === 'Public Relations Officer' ? 'selected' : '' }}>Public Relations Officer</option>
                                    <option value="Business Manager" {{ old('position') === 'Business Manager' ? 'selected' : '' }}>Business Manager</option>
                                    <option value="Committee Chair" {{ old('position') === 'Committee Chair' ? 'selected' : '' }}>Committee Chair</option>
                                    <option value="Other" {{ old('position') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('position')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Adviser Position (shown only when adviser is selected) -->
                            <div x-show="role === 'adviser'" x-transition>
                                <label for="adviser_position" class="block text-sm font-medium text-gray-700 mb-2">Adviser Type <span class="text-red-500">*</span></label>
                                <select name="position" id="adviser_position"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                        x-model="position">
                                    <option value="">Select Adviser Type</option>
                                    <option value="Faculty Adviser" {{ old('position') === 'Faculty Adviser' ? 'selected' : '' }}>Faculty Adviser</option>
                                    <option value="Co-Adviser" {{ old('position') === 'Co-Adviser' ? 'selected' : '' }}>Co-Adviser</option>
                                    <option value="Senior Adviser" {{ old('position') === 'Senior Adviser' ? 'selected' : '' }}>Senior Adviser</option>
                                    <option value="Technical Adviser" {{ old('position') === 'Technical Adviser' ? 'selected' : '' }}>Technical Adviser</option>
                                </select>
                                @error('position')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                                <!-- Adviser Information Notice -->
                                <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Adviser Role Information</h3>
                                            <p class="text-sm text-blue-700 mt-1">Advisers have special permissions including the ability to certify club renewals and provide guidance to the organization.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level <span class="text-red-500">*</span></label>
                                    <select name="year_level" id="year_level" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="5th Year" {{ old('year_level') === '5th Year' ? 'selected' : '' }}>5th Year</option>
                                        <option value="Graduate" {{ old('year_level') === 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Course and Phone -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course/Program <span class="text-red-500">*</span></label>
                                    <input type="text" name="course" id="course" value="{{ old('course') }}" required
                                           placeholder="e.g., BS Computer Science"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone" 
                                           value="{{ old('phone') }}" 
                                           required
                                           maxlength="11"
                                           pattern="[0-9]{11}"
                                           placeholder="09XXXXXXXXX"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                           oninput="formatPhoneNumber(this)">
                                    <p class="text-xs text-gray-500 mt-1">11 digits (e.g., 09171234567)</p>
                                </div>
                            </div>

                            <!-- Security Verification -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="text-sm font-medium text-yellow-800 mb-1">Security Verification Required</h3>
                                            <p class="text-sm text-yellow-700">Please enter your current password to confirm this action.</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Current Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="current_password" id="current_password" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('current_password') border-red-500 @enderror"
                                           placeholder="Enter your current password to verify">
                                    @error('current_password')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-1">This verifies that you have permission to add new members/officers.</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex space-x-4 pt-6">
                                <button type="submit"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Add to Club
                                </button>
                                <a href="{{ route('club.officer.dashboard') }}"
                                   class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                                    Cancel
                                </a>
                            </div>
                        </form>
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
