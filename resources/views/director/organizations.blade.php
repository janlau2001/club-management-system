<x-dashboard-layout>
    <x-slot name="title">Organizations</x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Organizations</h1>
                <p class="text-gray-600">Manage all registered clubs and organizations</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search organizations..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Types</option>
                        @if(isset($clubTypes))
                            @foreach($clubTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        @else
                            <option value="Academic" {{ request('type') == 'Academic' ? 'selected' : '' }}>Academic</option>
                            <option value="Interest" {{ request('type') == 'Interest' ? 'selected' : '' }}>Interest</option>
                        @endif
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Status</option>
                        @if(isset($statuses))
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        @else
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="pending_renewal" {{ request('status') == 'pending_renewal' ? 'selected' : '' }}>Pending Renewal</option>
                        @endif
                    </select>
                </div>
                <div>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Departments</option>
                        @if(isset($departments))
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                            @endforeach
                        @else
                            <option value="SASTE" {{ request('department') == 'SASTE' ? 'selected' : '' }}>SASTE</option>
                            <option value="SNAHS" {{ request('department') == 'SNAHS' ? 'selected' : '' }}>SNAHS</option>
                            <option value="SITE" {{ request('department') == 'SITE' ? 'selected' : '' }}>SITE</option>
                            <option value="SBAHM" {{ request('department') == 'SBAHM' ? 'selected' : '' }}>SBAHM</option>
                            <option value="BEU" {{ request('department') == 'BEU' ? 'selected' : '' }}>BEU</option>
                            <option value="SOM" {{ request('department') == 'SOM' ? 'selected' : '' }}>SOM</option>
                            <option value="GRADUATE SCHOOL" {{ request('department') == 'GRADUATE SCHOOL' ? 'selected' : '' }}>GRADUATE SCHOOL</option>
                        @endif
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                    <button type="button" onclick="clearFilters()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>

        @if($clubs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clubs as $club)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $club->name }}</h3>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($club->status === 'active') bg-green-100 text-green-800
                                @elseif($club->status === 'suspended') bg-red-100 text-red-800
                                @elseif($club->status === 'pending_renewal') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Department:</strong> {{ $club->department }}</p>
                            <p><strong>Type:</strong> {{ $club->club_type }}</p>
                            <p><strong>Members:</strong> {{ $club->member_count }}</p>
                            <p><strong>Registered:</strong> {{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('director.organization.show', $club) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No organizations found</h3>
                <p class="text-gray-500">
                    @if(request()->hasAny(['search', 'type', 'status', 'department']) && (request('search') || request('type') !== 'all' || request('status') !== 'all' || request('department') !== 'all'))
                        No organizations match your current filters. Try adjusting your search criteria.
                    @else
                        There are no organizations at this time.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <script>
        // Clear filters function
        function clearFilters() {
            // Simply redirect to the page without any query parameters
            window.location.href = window.location.pathname;
        }

        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (!form) return;

            const selects = form.querySelectorAll('select');

            selects.forEach(select => {
                select.addEventListener('change', function() {
                    form.submit();
                });
            });

            // Submit on Enter key for search input
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        form.submit();
                    }
                });
            }
        });
    </script>
</x-dashboard-layout>