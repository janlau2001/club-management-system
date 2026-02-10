<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - {{ $clubUser->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Edit Member</h1>
                        <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                        <p class="text-white opacity-75 text-sm">Editing: {{ $clubUser->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('club.officer.manage-members') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30">
                            ← Back to Members
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Edit Member Information</h2>
                    <p class="text-gray-600 mt-1">Update member details and role information</p>
                </div>

                <form method="POST" action="{{ route('club.officer.member.update', $clubUser) }}" class="p-6" x-data="{ role: '{{ $clubUser->role }}' }">
                    @csrf
                    
                    <!-- Current Member Info -->
                    <div class="mb-8 p-4 @if($clubUser->id === $currentUser->id) bg-blue-50 border border-blue-200 @else bg-gray-50 @endif rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2 flex items-center">
                            Current Information
                            @if($clubUser->id === $currentUser->id)
                                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    This is you
                                </span>
                            @endif
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span> {{ $clubUser->name }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Email:</span> {{ $clubUser->email }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">
                                    @if($clubUser->role === 'adviser')
                                        Professor ID:
                                    @else
                                        Student ID:
                                    @endif
                                </span> 
                                @if($clubUser->role === 'adviser')
                                    {{ $clubUser->professor_id ?? 'N/A' }}
                                @else
                                    {{ $clubUser->student_id }}
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Role:</span> 
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($clubUser->role === 'officer') bg-purple-100 text-purple-800
                                    @elseif($clubUser->role === 'adviser') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $clubUser->getDisplayRole() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required 
                                       value="{{ old('name', $clubUser->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                       placeholder="Enter full name">
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" required 
                                       value="{{ old('email', $clubUser->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                                       placeholder="Enter email address">
                                @error('email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role !== 'adviser'" x-transition>
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                                <input type="text" name="student_id" id="student_id"
                                       value="{{ old('student_id', $clubUser->student_id) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('student_id') border-red-500 @enderror"
                                       placeholder="Enter student ID"
                                       :required="role !== 'adviser'">
                                @error('student_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role === 'adviser'" x-transition>
                                <label for="professor_id" class="block text-sm font-medium text-gray-700 mb-2">Professor ID <span class="text-red-500">*</span></label>
                                <input type="text" name="professor_id" id="professor_id"
                                       value="{{ old('professor_id', $clubUser->professor_id) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('professor_id') border-red-500 @enderror"
                                       placeholder="Enter professor ID"
                                       :required="role === 'adviser'">
                                @error('professor_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
                        @if($clubUser->id === $currentUser->id)
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <p class="text-sm text-yellow-800">
                                        <strong>Warning:</strong> You cannot change your own role from officer to member. This prevents you from losing management access.
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Role in Club <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg @if($clubUser->id === $currentUser->id) cursor-not-allowed opacity-50 @else cursor-pointer hover:bg-gray-50 @endif transition-colors">
                                    <input type="radio" name="role" value="member" x-model="role"
                                           class="sr-only" {{ old('role', $clubUser->role) === 'member' ? 'checked' : '' }}
                                           @if($clubUser->id === $currentUser->id) disabled @endif>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center"
                                             :class="role === 'member' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-white rounded-full" x-show="role === 'member'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Regular Member</div>
                                            <div class="text-sm text-gray-500">Standard club member with basic privileges
                                                @if($clubUser->id === $currentUser->id)
                                                    <span class="text-red-500">(Cannot change your own role)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="role" value="officer" x-model="role"
                                           class="sr-only" {{ old('role', $clubUser->role) === 'officer' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center"
                                             :class="role === 'officer' ? 'border-purple-500 bg-purple-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-white rounded-full" x-show="role === 'officer'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Officer</div>
                                            <div class="text-sm text-gray-500">Club officer with management privileges</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="role" value="adviser" x-model="role"
                                           class="sr-only" {{ old('role', $clubUser->role) === 'adviser' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center"
                                             :class="role === 'adviser' ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-white rounded-full" x-show="role === 'adviser'"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Adviser</div>
                                            <div class="text-sm text-gray-500">Faculty adviser with special privileges</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Officer Position (shown only when officer is selected) -->
                        <div x-show="role === 'officer'" x-transition>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Officer Position <span class="text-red-500">*</span></label>
                            <select name="position" id="position" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                    :required="role === 'officer'">
                                <option value="">Select Position</option>
                                <option value="President" {{ old('position', $clubUser->position) === 'President' ? 'selected' : '' }}>President</option>
                                <option value="Vice President" {{ old('position', $clubUser->position) === 'Vice President' ? 'selected' : '' }}>Vice President</option>
                                <option value="Secretary" {{ old('position', $clubUser->position) === 'Secretary' ? 'selected' : '' }}>Secretary</option>
                                <option value="Treasurer" {{ old('position', $clubUser->position) === 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                                <option value="Auditor" {{ old('position', $clubUser->position) === 'Auditor' ? 'selected' : '' }}>Auditor</option>
                                <option value="Public Relations Officer" {{ old('position', $clubUser->position) === 'Public Relations Officer' ? 'selected' : '' }}>Public Relations Officer</option>
                                <option value="Business Manager" {{ old('position', $clubUser->position) === 'Business Manager' ? 'selected' : '' }}>Business Manager</option>
                                <option value="Committee Chair" {{ old('position', $clubUser->position) === 'Committee Chair' ? 'selected' : '' }}>Committee Chair</option>
                                <option value="Other" {{ old('position', $clubUser->position) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('position')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adviser Position (shown only when adviser is selected) -->
                        <div x-show="role === 'adviser'" x-transition>
                            <label for="adviser_position" class="block text-sm font-medium text-gray-700 mb-2">Adviser Type <span class="text-red-500">*</span></label>
                            <select name="position" id="adviser_position"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                    :required="role === 'adviser'">
                                <option value="">Select Adviser Type</option>
                                <option value="Faculty Adviser" {{ old('position', $clubUser->position) === 'Faculty Adviser' ? 'selected' : '' }}>Faculty Adviser</option>
                                <option value="Co-Adviser" {{ old('position', $clubUser->position) === 'Co-Adviser' ? 'selected' : '' }}>Co-Adviser</option>
                                <option value="Senior Adviser" {{ old('position', $clubUser->position) === 'Senior Adviser' ? 'selected' : '' }}>Senior Adviser</option>
                                <option value="Technical Adviser" {{ old('position', $clubUser->position) === 'Technical Adviser' ? 'selected' : '' }}>Technical Adviser</option>
                            </select>
                            @error('position')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div x-show="role !== 'adviser'" x-transition>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                                <select name="department" id="department"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                        :required="role !== 'adviser'">
                                    <option value="">Select Department</option>
                                    <option value="SASTE" {{ old('department', $clubUser->department) === 'SASTE' ? 'selected' : '' }}>SASTE</option>
                                    <option value="SBAHM" {{ old('department', $clubUser->department) === 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                                    <option value="SNAHS" {{ old('department', $clubUser->department) === 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                                    <option value="SITE" {{ old('department', $clubUser->department) === 'SITE' ? 'selected' : '' }}>SITE</option>
                                    <option value="BEU" {{ old('department', $clubUser->department) === 'BEU' ? 'selected' : '' }}>BEU</option>
                                    <option value="SOM" {{ old('department', $clubUser->department) === 'SOM' ? 'selected' : '' }}>SOM</option>
                                    <option value="GRADUATE SCHOOL" {{ old('department', $clubUser->department) === 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                                </select>
                                @error('department')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role === 'adviser'" x-transition>
                                <label for="department_office" class="block text-sm font-medium text-gray-700 mb-2">Department Office <span class="text-red-500">*</span></label>
                                <select name="department_office" id="department_office"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                        :required="role === 'adviser'">
                                    <option value="">Select Department Office</option>
                                    <option value="SASTE" {{ old('department_office', $clubUser->department_office) === 'SASTE' ? 'selected' : '' }}>SASTE</option>
                                    <option value="SNAHS" {{ old('department_office', $clubUser->department_office) === 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                                    <option value="SITE" {{ old('department_office', $clubUser->department_office) === 'SITE' ? 'selected' : '' }}>SITE</option>
                                    <option value="SBAHM" {{ old('department_office', $clubUser->department_office) === 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                                    <option value="BEU" {{ old('department_office', $clubUser->department_office) === 'BEU' ? 'selected' : '' }}>BEU</option>
                                    <option value="SOM" {{ old('department_office', $clubUser->department_office) === 'SOM' ? 'selected' : '' }}>SOM</option>
                                    <option value="GRADUATE SCHOOL" {{ old('department_office', $clubUser->department_office) === 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                                </select>
                                @error('department_office')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role !== 'adviser'" x-transition>
                                <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level <span class="text-red-500">*</span></label>
                                <select name="year_level" id="year_level"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                        :required="role !== 'adviser'">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ old('year_level', $clubUser->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level', $clubUser->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level', $clubUser->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level', $clubUser->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    <option value="5th Year" {{ old('year_level', $clubUser->year_level) === '5th Year' ? 'selected' : '' }}>5th Year</option>
                                    <option value="Graduate" {{ old('year_level', $clubUser->year_level) === 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                </select>
                                @error('year_level')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Security Verification -->
                    <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="text-lg font-medium text-red-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Security Verification Required
                        </h3>
                        <p class="text-red-700 text-sm mb-4">
                            For security purposes, please enter your current password to confirm these changes.
                        </p>
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-red-800 mb-2">
                                Your Current Password <span class="text-red-600">*</span>
                            </label>
                            <input type="password" name="current_password" id="current_password" required 
                                   class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors @error('current_password') border-red-500 @enderror"
                                   placeholder="Enter your current password">
                            @error('current_password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('club.officer.manage-members') }}" 
                           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Update Member
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
