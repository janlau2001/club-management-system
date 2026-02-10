<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details - {{ $clubUser->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-green-800 to-green-900 shadow-lg">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Member Details</h1>
                        <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                        <p class="text-white opacity-75 text-sm">Viewing: {{ $clubUser->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('club.officer.manage-members') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30">
                            ← Back to Members
                        </a>
                        <a href="{{ route('club.officer.member.edit', $clubUser) }}" 
                           class="bg-white text-green-800 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Edit Member
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Member Profile Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-8 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-20 w-20">
                            <div class="h-20 w-20 rounded-full bg-gradient-to-r 
                                @if($clubUser->role === 'adviser') from-green-400 to-green-600
                                @elseif($clubUser->role === 'officer') from-purple-400 to-purple-600 
                                @else from-blue-400 to-blue-600 @endif 
                                flex items-center justify-center shadow-lg">
                                <span class="text-xl font-bold text-white">
                                    {{ strtoupper(substr($clubUser->name, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $clubUser->name }}</h2>
                            <div class="flex items-center mt-2 space-x-3">
                                @if($clubUser->role === 'adviser')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $clubUser->getDisplayRole() }}
                                    </span>
                                @elseif($clubUser->role === 'officer')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 12a9.972 9.972 0 01-1.929 5.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 12c0-1.636-.491-3.154-1.343-4.243a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $clubUser->getDisplayRole() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                        </svg>
                                        Member
                                    </span>
                                @endif
                                
                                @if($clubUser->isOnline())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                        Offline
                                    </span>
                                @endif

                                @if($clubUser->id === $currentUser->id)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        This is you
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Member Information -->
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Full Name</label>
                            <p class="text-lg font-medium text-gray-900">{{ $clubUser->name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Email Address</label>
                            <p class="text-lg text-gray-900">{{ $clubUser->email }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                @if($clubUser->role === 'adviser')
                                    Professor ID
                                @else
                                    Student ID
                                @endif
                            </label>
                            <p class="text-lg text-gray-900">
                                @if($clubUser->role === 'adviser')
                                    {{ $clubUser->professor_id ?? 'N/A' }}
                                @else
                                    {{ $clubUser->student_id ?? 'N/A' }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                @if($clubUser->role === 'adviser')
                                    Department Office
                                @else
                                    Department
                                @endif
                            </label>
                            <p class="text-lg text-gray-900">
                                @if($clubUser->role === 'adviser')
                                    {{ $clubUser->department_office ?? 'N/A' }}
                                @else
                                    {{ $clubUser->department }}
                                @endif
                            </p>
                        </div>
                        
                        @if($clubUser->role !== 'adviser')
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Year Level</label>
                            <p class="text-lg text-gray-900">{{ $clubUser->year_level }}</p>
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Joined Date</label>
                            <p class="text-lg text-gray-900">{{ $clubUser->joined_date ? $clubUser->joined_date->format('F j, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Activity Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Current Status</label>
                            <div class="mt-2">
                                @if($clubUser->isOnline())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                        Online Now
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                        Offline
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Last Activity</label>
                            <p class="text-lg text-gray-900">
                                @if($clubUser->last_activity)
                                    {{ $clubUser->last_activity->diffForHumans() }}
                                @else
                                    Never logged in
                                @endif
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Account Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($clubUser->status === 'active') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($clubUser->status) }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Member Since</label>
                            <p class="text-lg text-gray-900">
                                {{ $clubUser->created_at ? $clubUser->created_at->format('F j, Y') : 'N/A' }}
                                @if($clubUser->created_at)
                                    <span class="text-sm text-gray-500">({{ $clubUser->created_at->diffForHumans() }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('club.officer.member.edit', $clubUser) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Member
                        </a>
                        
                        @if($clubUser->id !== $currentUser->id)
                            <button onclick="showRemoveModal({{ $clubUser->id }}, '{{ $clubUser->name }}')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Remove Member
                            </button>
                        @else
                            <span class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-medium cursor-not-allowed flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                Cannot Remove Yourself
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Remove Member Modal -->
    <div id="removeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 class="text-lg font-medium text-gray-900">Remove Member</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Are you sure you want to remove <span id="memberName" class="font-medium">{{ $clubUser->name }}</span> from the club? This action cannot be undone.
                    </p>
                    
                    <form method="POST" action="{{ route('club.officer.member.remove', $clubUser) }}" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="remove_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter your password to confirm <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="remove_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="Your current password">
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" onclick="hideRemoveModal()" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Remove Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRemoveModal(memberId, memberName) {
            document.getElementById('removeModal').classList.remove('hidden');
        }

        function hideRemoveModal() {
            document.getElementById('removeModal').classList.add('hidden');
        }
    </script>
</body>
</html>
