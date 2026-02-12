<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Manage Members - {{ $club->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Ensure table doesn't break on smaller screens */
        @media (max-width: 1024px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Ensure action buttons are always visible */
        .action-buttons {
            min-width: 180px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-[#29553c] to-[#031a0a]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">
                            @if($clubUser->role === 'member')
                                View Members
                            @else
                                Manage Members
                            @endif
                        </h1>
                        <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                        <p class="text-white opacity-75 text-sm">{{ $clubUser->name }} ({{ $clubUser->getDisplayRole() }})</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ $clubUser->role === 'member' ? route('club.member.dashboard') : route('club.officer.dashboard') }}"
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 text-sm font-medium transition-colors border border-white/30">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Title Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Club Members & Officers</h2>
                <p class="text-gray-600 mt-1">
                    @if($clubUser->role === 'member')
                        View all club members and officers
                    @else
                        View, edit, and manage all club members
                    @endif
                </p>
            </div>

            <!-- Role Filter Tabs -->
            <div class="bg-white border border-gray-200 mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <!-- All Members Tab -->
                        <a href="{{ route('club.officer.manage-members', ['role' => 'all']) }}" 
                           class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                                  {{ $roleFilter === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ $roleFilter === 'all' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            All Members
                            <span class="ml-2 py-0.5 px-2.5 text-xs font-medium
                                         {{ $roleFilter === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $allCount }}
                            </span>
                        </a>

                        <!-- Advisers Tab -->
                        <a href="{{ route('club.officer.manage-members', ['role' => 'adviser']) }}" 
                           class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                                  {{ $roleFilter === 'adviser' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ $roleFilter === 'adviser' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                            </svg>
                            Advisers
                            <span class="ml-2 py-0.5 px-2.5 text-xs font-medium
                                         {{ $roleFilter === 'adviser' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $adviserCount }}
                            </span>
                        </a>

                        <!-- Officers Tab -->
                        <a href="{{ route('club.officer.manage-members', ['role' => 'officer']) }}" 
                           class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                                  {{ $roleFilter === 'officer' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ $roleFilter === 'officer' ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Officers
                            <span class="ml-2 py-0.5 px-2.5 text-xs font-medium
                                         {{ $roleFilter === 'officer' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $officerCount }}
                            </span>
                        </a>

                        <!-- Regular Members Tab -->
                        <a href="{{ route('club.officer.manage-members', ['role' => 'member']) }}" 
                           class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                                  {{ $roleFilter === 'member' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ $roleFilter === 'member' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Regular Members
                            <span class="ml-2 py-0.5 px-2.5 text-xs font-medium
                                         {{ $roleFilter === 'member' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $memberCount }}
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Tab Content Description -->
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <p class="text-sm text-gray-600">
                        @if($roleFilter === 'all')
                            Showing <span class="font-medium text-gray-900">all {{ $clubUsers->count() }} members</span> in your organization
                        @elseif($roleFilter === 'adviser')
                            Showing <span class="font-medium text-gray-900">{{ $clubUsers->count() }} adviser(s)</span> in your organization
                        @elseif($roleFilter === 'officer')
                            Showing <span class="font-medium text-gray-900">{{ $clubUsers->count() }} officer(s)</span> in your organization
                        @else
                            Showing <span class="font-medium text-gray-900">{{ $clubUsers->count() }} regular member(s)</span> in your organization
                        @endif
                    </p>
                </div>
            </div>

            <!-- Members Table -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto table-container">
                    <table class="w-full divide-y divide-gray-200" style="min-width: 1200px;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '30%' : '25%' }};">Member</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '15%' : '12%' }};">Role</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '15%' : '12%' }};">Department</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '12%' : '10%' }};">Year Level</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '12%' : '10%' }};">Status</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: {{ $clubUser->role === 'member' ? '16%' : '12%' }};">Joined</th>
                                @if($clubUser->role !== 'member')
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 19%;">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($clubUsers as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r
                                                    @if($member->role === 'adviser') from-green-400 to-green-600
                                                    @elseif($member->role === 'officer') from-purple-400 to-purple-600
                                                    @else from-blue-400 to-blue-600 @endif
                                                    flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <a href="{{ route('club.officer.member.view', $member) }}" class="text-sm font-medium text-gray-900 hover:text-green-600 transition-colors">{{ $member->name }}</a>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                <div class="text-xs text-gray-400">
                                                    @if($member->role === 'adviser')
                                                        ID: {{ $member->professor_id ?? $member->student_id }}
                                                    @else
                                                        ID: {{ $member->student_id }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        {!! $member->getRoleBadgeHtml() !!}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($member->role === 'adviser')
                                            {{ $member->department_office ?? $member->department }}
                                        @else
                                            {{ $member->department }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($member->role === 'adviser')
                                            <span class="text-gray-400">-</span>
                                        @else
                                            {{ $member->year_level }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @if($member->isOnline())
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Online
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                                Offline
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}
                                    </td>
                                    @if($clubUser->role !== 'member')
                                        <td class="px-4 py-4 text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-1 sm:space-y-0 sm:space-x-1 action-buttons">
                                                <a href="{{ route('club.officer.member.view', $member) }}"
                                                   class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2 py-1 text-xs transition-colors whitespace-nowrap">
                                                    View
                                                </a>
                                                @if(($clubUser->position === 'President' || $clubUser->role === 'adviser') && $member->id !== $clubUser->id)
                                                    <button onclick="showChangeRoleModal({{ $member->id }}, '{{ $member->name }}', '{{ $member->role }}')"
                                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 text-xs transition-colors whitespace-nowrap">
                                                        Change Role
                                                    </button>
                                                    <button onclick="showRemoveModal({{ $member->id }}, '{{ $member->name }}')"
                                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 text-xs transition-colors whitespace-nowrap">
                                                        Remove
                                                    </button>
                                                @elseif($member->id === $clubUser->id)
                                                    <span class="text-gray-400 bg-gray-50 px-2 py-1 text-xs cursor-not-allowed whitespace-nowrap">
                                                        You
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 bg-gray-50 px-2 py-1 text-xs cursor-not-allowed whitespace-nowrap">
                                                        Restricted
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No members found</p>
                                            <p class="text-sm">Start by adding some members to your club.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    @php
        // Prepare all Blade variables for use in JavaScript
        $hasSuccess = session('success') ? 'true' : 'false';
        $successMsg = session('success', '');
        $hasValidationErrors = $errors->any() && (old('role') !== null || old('position') !== null || old('current_password') !== null);
        $oldRole = old('role', '');
        $oldPosition = old('position', '');
    @endphp

    <!-- Success Message Modal -->
    <div id="successModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full hidden flex items-start justify-center pt-16" style="z-index: 1000;">
        <div class="relative w-full max-w-md mx-4 bg-white border border-gray-200">
            <!-- Modal Header -->
            <div class="bg-gray-900 px-6 py-4">
                <h3 class="text-base font-semibold text-white">Success!</h3>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600" id="successModalMessage"></p>
            </div>
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="hideSuccessModal()" 
                        class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Stay on Page
                </button>
                <button type="button" onclick="refreshPage()" 
                        class="px-5 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                    Refresh Page
                </button>
            </div>
        </div>
    </div>

    <!-- Success message display -->
    <script>
        var hasSuccess = {{ $hasSuccess }};
        var successMessage = {!! json_encode($successMsg) !!};
        
        if (hasSuccess && successMessage) {
            setTimeout(function() {
                document.getElementById('successModalMessage').textContent = successMessage;
                document.getElementById('successModal').classList.remove('hidden');
            }, 500);
        }

        function hideSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        function refreshPage() {
            window.location.reload(true);
        }
    </script>

    @if($clubUser->position === 'President' || $clubUser->role === 'adviser')
        <!-- Change Role Modal -->
        <div id="changeRoleModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full hidden flex items-start justify-center pt-16" style="z-index: 1000;">
            <div class="relative w-full max-w-md mx-4 bg-white border border-gray-200">
                <!-- Modal Header -->
                <div class="bg-gray-900 px-6 py-4">
                    <h3 class="text-base font-semibold text-white">Change Member Role</h3>
                    <p class="text-xs text-gray-300 mt-1">
                        Updating role for <span id="roleChangeMemberName" class="font-medium text-white"></span>
                    </p>
                </div>

                <form id="changeRoleForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="px-6 py-5 space-y-5">
                        <!-- Role Selection Cards -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Select New Role <span class="text-red-500">*</span></label>
                            <input type="hidden" name="role" id="new_role" required>
                            <div class="grid grid-cols-3 gap-3 mt-2">
                                <button type="button" data-role="member"
                                        class="role-card group border border-gray-200 p-3 text-center transition-all hover:border-gray-400 focus:outline-none"
                                        onclick="selectRole('member')">
                                    <div class="mx-auto w-8 h-8 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Member</span>
                                </button>
                                <button type="button" data-role="officer"
                                        class="role-card group border border-gray-200 p-3 text-center transition-all hover:border-gray-400 focus:outline-none"
                                        onclick="selectRole('officer')">
                                    <div class="mx-auto w-8 h-8 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Officer</span>
                                </button>
                                <button type="button" data-role="adviser"
                                        class="role-card group border border-gray-200 p-3 text-center transition-all hover:border-gray-400 focus:outline-none"
                                        onclick="selectRole('adviser')">
                                    <div class="mx-auto w-8 h-8 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Adviser</span>
                                </button>
                            </div>
                            @error('role')
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position Field (conditional) -->
                        <div id="positionField" class="hidden">
                            <label for="position" class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                   class="w-full mt-2 px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="e.g., Vice President, Secretary">
                            @error('position')
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="role_change_password" class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Password Confirmation <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="role_change_password" required
                                   class="w-full mt-2 px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="Enter your password to confirm">
                            @error('current_password')
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
                        <button type="button" onclick="hideChangeRoleModal()"
                                class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="changeRoleSubmitBtn" disabled
                                class="px-5 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors">
                            Change Role
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function selectRole(role) {
            document.getElementById('new_role').value = role;
            document.getElementById('changeRoleSubmitBtn').disabled = false;
            
            // Update card styles
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('border-gray-900', 'bg-gray-50');
                card.classList.add('border-gray-200');
                card.querySelector('svg').classList.remove('text-gray-900');
                card.querySelector('svg').classList.add('text-gray-400');
            });
            const selected = document.querySelector('.role-card[data-role="' + role + '"]');
            selected.classList.remove('border-gray-200');
            selected.classList.add('border-gray-900', 'bg-gray-50');
            selected.querySelector('svg').classList.remove('text-gray-400');
            selected.querySelector('svg').classList.add('text-gray-900');

            // Toggle position field
            const positionField = document.getElementById('positionField');
            const positionInput = document.getElementById('position');
            if (role === 'officer') {
                positionField.classList.remove('hidden');
                positionInput.required = true;
                positionInput.value = '';
                positionInput.focus();
            } else if (role === 'adviser') {
                positionField.classList.add('hidden');
                positionInput.required = false;
                positionInput.value = 'Club Adviser';
            } else {
                positionField.classList.add('hidden');
                positionInput.required = false;
                positionInput.value = '';
            }
        }

        function showChangeRoleModal(memberId, memberName, currentRole) {
            document.getElementById('roleChangeMemberName').textContent = memberName;
            document.getElementById('changeRoleForm').action = '/club/officer/member/' + memberId + '/change-role';
            document.getElementById('new_role').value = '';
            document.getElementById('position').value = '';
            document.getElementById('role_change_password').value = '';
            document.getElementById('positionField').classList.add('hidden');
            document.getElementById('changeRoleSubmitBtn').disabled = true;
            
            // Reset all role cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('border-gray-900', 'bg-gray-50');
                card.classList.add('border-gray-200');
                card.querySelector('svg').classList.remove('text-gray-900');
                card.querySelector('svg').classList.add('text-gray-400');
            });
            
            document.getElementById('changeRoleModal').classList.remove('hidden');
        }

        function hideChangeRoleModal() {
            document.getElementById('changeRoleModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle validation errors - reopen modal with old values
            var hasErrors = {{ $hasValidationErrors ? 'true' : 'false' }};
            var oldRole = {!! json_encode($oldRole) !!};
            var oldPosition = {!! json_encode($oldPosition) !!};

            if (hasErrors) {
                document.getElementById('changeRoleModal').classList.remove('hidden');
                
                if (oldRole) {
                    selectRole(oldRole);
                }
                
                if (oldPosition) {
                    document.getElementById('position').value = oldPosition;
                }
            }
        });
        </script>
    @endif

    @if($clubUser->role !== 'member')
        <!-- Remove Member Modal -->
        <div id="removeModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full hidden flex items-start justify-center pt-16" style="z-index: 1000;">
            <div class="relative w-full max-w-md mx-4 bg-white border border-gray-200">
                <!-- Modal Header -->
                <div class="bg-gray-900 px-6 py-4">
                    <h3 class="text-base font-semibold text-white">Remove Member</h3>
                    <p class="text-xs text-gray-300 mt-1">
                        Removing <span id="memberName" class="font-medium text-white"></span> from the club
                    </p>
                </div>

                <form id="removeForm" method="POST">
                    @csrf
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-500 mb-4">
                            Are you sure you want to remove this member? This action cannot be undone.
                        </p>
                        <div>
                            <label for="remove_password" class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Password Confirmation <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="remove_password" required
                                   class="w-full mt-2 px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors"
                                   placeholder="Enter your password to confirm">
                            <div id="removePasswordError" class="text-sm text-red-600 mt-1 hidden"></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end space-x-3">
                        <button type="button" onclick="hideRemoveModal()" 
                                class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
                            Remove Member
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function showRemoveModal(memberId, memberName) {
            document.getElementById('memberName').textContent = memberName;
            document.getElementById('removeForm').action = '/club/officer/member/' + memberId + '/remove';
            document.getElementById('remove_password').value = '';
            document.getElementById('removePasswordError').classList.add('hidden');
            document.getElementById('removeModal').classList.remove('hidden');
        }

        function hideRemoveModal() {
            document.getElementById('removeModal').classList.add('hidden');
        }
        </script>
    @endif
</body>
</html>
