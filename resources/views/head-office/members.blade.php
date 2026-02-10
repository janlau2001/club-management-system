<x-dashboard-layout>
    <x-slot name="title">Members</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Club Members</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage members across all registered organizations</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="expandAll()" class="border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center space-x-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                <span>Expand All</span>
            </button>
            <button onclick="collapseAll()" class="border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center space-x-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11H3m3 0l3-3m-3 3l3 3m8-9l3 3m-3-3l3-3m-3 3h6"></path>
                </svg>
                <span>Collapse All</span>
            </button>
            <button onclick="openReportModal()" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center space-x-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>

    <!-- Member Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Members</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalMembers) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Officers</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalOfficers) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Advisers</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalAdvisers ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Active Clubs</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($activeClubs) }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Clubs</p>
                <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalClubs) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('head-office.members') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Search Clubs</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by club name..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Club Type</label>
                    <select name="type" class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                        <option value="all">All Types</option>
                        @foreach($clubTypes as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                        <option value="all">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Department</label>
                    <select name="department" class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                        <option value="all">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-5 flex justify-end space-x-2">
                <a href="{{ route('head-office.members') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">Clear Filters</a>
                <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2 text-sm font-medium transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Clubs and Members -->
    <div class="space-y-3">
        @forelse($clubs as $club)
            <div class="bg-white border border-gray-200 overflow-hidden">
                <!-- Club Header (Clickable) -->
                <div class="px-6 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors duration-150"
                     onclick="toggleClub('club-{{ $club->id }}')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">{{ $club->name }}</h3>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="text-xs text-gray-600">{{ $club->department }}</span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-600">{{ $club->club_type }}</span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="px-2 py-0.5 text-xs font-medium
                                        @if($club->status === 'active') bg-green-50 text-green-700 border border-green-200
                                        @elseif($club->status === 'suspended') bg-red-50 text-red-700 border border-red-200
                                        @elseif($club->status === 'pending_renewal') bg-yellow-50 text-yellow-700 border border-yellow-200
                                        @else bg-gray-50 text-gray-700 border border-gray-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="text-right">
                                <div class="text-lg font-semibold text-gray-900">{{ $club->member_count }}</div>
                                <div class="text-xs text-gray-500">Members</div>
                            </div>
                            <!-- Expand/Collapse Icon -->
                            <div class="transform transition-transform duration-200" id="icon-club-{{ $club->id }}">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Club Members and Officers (Collapsible Content) -->
                <div class="hidden" id="content-club-{{ $club->id }}">
                    <div class="p-6 bg-gray-50">
                        <!-- Club Actions -->
                        <div class="flex justify-end mb-6">
                            <a href="{{ route('head-office.reports.single-club', $club) }}"
                               class="border border-gray-300 hover:border-gray-900 text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium flex items-center space-x-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Generate Club Report</span>
                            </a>
                        </div>
                        <!-- Officers Section -->
                        @if($club->officers->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">
                                    Officers ({{ $club->officers->count() }})
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($club->officers as $officer)
                                        <div class="bg-white border border-gray-200 p-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="h-10 w-10 border border-gray-300 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ strtoupper(substr($officer->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $officer->name }}</div>
                                                    <div class="text-xs text-gray-600 mt-0.5">{{ $officer->position }}</div>
                                                    <div class="text-xs text-gray-500 mt-0.5 truncate">{{ $officer->email }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Advisers Section -->
                        @php
                            $advisers = $club->clubUsers->where('role', 'adviser');
                        @endphp
                        @if($advisers->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">
                                    Advisers ({{ $advisers->count() }})
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($advisers as $adviser)
                                        <div class="bg-white border border-gray-200 p-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="h-10 w-10 border border-gray-300 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ strtoupper(substr($adviser->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $adviser->name }}</div>
                                                    <div class="text-xs text-gray-600 mt-0.5">{{ $adviser->position ?? 'Club Adviser' }}</div>
                                                    <div class="text-xs text-gray-500 mt-0.5 truncate">{{ $adviser->email }}</div>
                                                    @if($adviser->professor_id)
                                                        <div class="text-xs text-gray-500 mt-1">Prof ID: {{ $adviser->professor_id }}</div>
                                                    @endif
                                                    @if($adviser->department_office)
                                                        <div class="text-xs text-gray-500">{{ $adviser->department_office }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Members Section -->
                        @if($club->members->count() > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">
                                    Members ({{ $club->members->count() }})
                                </h4>
                                <div class="overflow-x-auto bg-white border border-gray-200">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 border-b border-gray-200">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Member</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Student ID</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Department</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Year Level</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($club->members as $member)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-8 w-8 border border-gray-300 flex items-center justify-center flex-shrink-0">
                                                                <span class="text-xs font-medium text-gray-600">
                                                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                                                </span>
                                                            </div>
                                                            <div class="ml-3">
                                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                                <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                        @if($member->role === 'adviser')
                                                            {{ $member->professor_id ?? 'N/A' }}
                                                        @else
                                                            {{ $member->student_id }}
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $member->department }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $member->year_level }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">No members yet</h3>
                                <p class="text-sm text-gray-500">This club doesn't have any registered members.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-2">No clubs found</h3>
                <p class="text-sm text-gray-500 mb-4">No clubs match your current filter criteria.</p>
                <a href="{{ route('head-office.members') }}" class="text-gray-900 hover:text-gray-700 font-medium text-sm underline">
                    Clear all filters
                </a>
            </div>
        @endforelse
    </div>

    <!-- Report Selection Modal -->
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Generate Club Members Report</h3>
                        <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('head-office.reports.members') }}" method="POST" id="reportForm">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-3">Select clubs to include in the report:</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 p-3">
                                <div class="flex items-center mb-3">
                                    <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="selectAll" class="ml-2 text-sm font-medium text-gray-900">Select All Clubs</label>
                                </div>
                                <hr class="mb-3">
                                @foreach($clubs as $club)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               name="club_ids[]"
                                               value="{{ $club->id }}"
                                               id="club_{{ $club->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded club-checkbox">
                                        <label for="club_{{ $club->id }}" class="ml-2 text-sm text-gray-700 flex-1">
                                            <span class="font-medium">{{ $club->name }}</span>
                                            <span class="text-gray-500 ml-2">({{ $club->department }} • {{ $club->member_count }} members)</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200">
                            <button type="button"
                                    onclick="closeReportModal()"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2 text-sm font-medium flex items-center space-x-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Generate PDF Report</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleClub(clubId) {
            const content = document.getElementById('content-' + clubId);
            const icon = document.getElementById('icon-' + clubId);

            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                content.classList.add('block');

                // Rotate icon to point up
                icon.style.transform = 'rotate(180deg)';

                // Add smooth slide down animation
                content.style.maxHeight = '0px';
                content.style.overflow = 'hidden';
                content.style.transition = 'max-height 0.3s ease-out';

                // Force reflow
                content.offsetHeight;

                // Set max height to scroll height for smooth animation
                content.style.maxHeight = content.scrollHeight + 'px';

                // Remove max-height after animation completes
                setTimeout(() => {
                    content.style.maxHeight = 'none';
                    content.style.overflow = 'visible';
                }, 300);

            } else {
                // Hide content with animation
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.overflow = 'hidden';
                content.style.transition = 'max-height 0.3s ease-in';

                // Force reflow
                content.offsetHeight;

                // Collapse
                content.style.maxHeight = '0px';

                // Rotate icon back to point down
                icon.style.transform = 'rotate(0deg)';

                // Hide after animation
                setTimeout(() => {
                    content.classList.add('hidden');
                    content.classList.remove('block');
                    content.style.maxHeight = '';
                    content.style.overflow = '';
                    content.style.transition = '';
                }, 300);
            }
        }

        // Add expand/collapse all functionality
        function expandAll() {
            const allClubs = document.querySelectorAll('[id^="content-club-"]');
            allClubs.forEach(content => {
                if (content.classList.contains('hidden')) {
                    const clubId = content.id.replace('content-', '');
                    toggleClub(clubId);
                }
            });
        }

        function collapseAll() {
            const allClubs = document.querySelectorAll('[id^="content-club-"]');
            allClubs.forEach(content => {
                if (!content.classList.contains('hidden')) {
                    const clubId = content.id.replace('content-', '');
                    toggleClub(clubId);
                }
            });
        }

        // Report Modal Functions
        function openReportModal() {
            document.getElementById('reportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Select All Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const clubCheckboxes = document.querySelectorAll('.club-checkbox');

            selectAllCheckbox.addEventListener('change', function() {
                clubCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            clubCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(clubCheckboxes).every(cb => cb.checked);
                    const noneChecked = Array.from(clubCheckboxes).every(cb => !cb.checked);

                    if (allChecked) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.indeterminate = false;
                    } else if (noneChecked) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    } else {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = true;
                    }
                });
            });

            // Form validation
            document.getElementById('reportForm').addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.club-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one club to generate a report.');
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });
    </script>
</x-dashboard-layout>
