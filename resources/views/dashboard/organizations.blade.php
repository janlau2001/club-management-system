<x-dashboard-layout>
    <x-slot name="title">Organizations</x-slot>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Organizations</h1>
                <p class="text-gray-600">Manage all registered clubs and organizations</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Logged in as:</div>
                <div class="text-sm font-medium text-gray-900" id="currentAdminDisplay">
                    @if(session('user'))
                        {{ session('user')->name }}
                    @endif
                    @if(session('admin_role') === 'head_student_affairs')
                        (Head of Student Affairs)
                    @elseif(session('admin_role') === 'director_student_affairs')
                        (Director)
                    @elseif(session('admin_role') === 'vp_academics')
                        (VP for Academics)
                    @elseif(session('admin_role') === 'dean')
                        (Dean)
                    @else
                        (Admin)
                    @endif
                </div>
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

        <!-- Organizations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($clubs as $club)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $club->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $club->department }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($club->status === 'active') bg-green-100 text-green-800
                        @elseif($club->status === 'suspended') bg-red-100 text-red-800
                        @elseif($club->status === 'pending_renewal') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                    </span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.196M17 20v-2a3 3 0 00-3-3H8a3 3 0 00-3 3v2m14 0H3m14 0v-2a3 3 0 00-3-3H8a3 3 0 00-3 3v2"></path>
                        </svg>
                        {{ $club->member_count }} members
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm0 0v4a2 2 0 002 2h4a2 2 0 002-2v-4"></path>
                        </svg>
                        Registered {{ $club->date_registered->format('M d, Y') }}
                    </div>
                </div>

                <div class="flex space-x-2">
                    @if(request()->routeIs('head-office.*'))
                        <a href="{{ route('head-office.organization.show', $club) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm text-center">
                            View Details
                        </a>
                    @elseif(request()->routeIs('director.*'))
                        <a href="{{ route('director.organization.show', $club) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm text-center">
                            View Details
                        </a>
                    @elseif(request()->routeIs('vp.*'))
                        <a href="{{ route('vp.organization.show', $club) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm text-center">
                            View Details
                        </a>
                    @elseif(request()->routeIs('dean.*'))
                        <a href="{{ route('dean.organization.show', $club) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm text-center">
                            View Details
                        </a>
                    @else
                        {{-- Default dashboard routes --}}
                        <a href="{{ route('dashboard.organization.show', $club) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm text-center">
                            View Details
                        </a>
                        @if($club->status === 'active')
                            <button type="button"
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                                    onclick="openConfirmModal('suspend', '{{ $club->name }}', '{{ route('dashboard.organization.suspend', $club) }}')">
                                Suspend
                            </button>
                        @else
                            <button type="button"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                                    onclick="openConfirmModal('resume', '{{ $club->name }}', '{{ route('dashboard.organization.activate', $club) }}')">
                                Resume
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
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
            @endforelse
        </div>
    </div>

    <script>
        // Set navigation flag before any navigation
        function setNavigationFlag() {
            // Simplified - no server call needed
        }

        // Clear filters function
        function clearFilters() {
            // Set navigation flag and redirect to clear filters
            setNavigationFlag();
            setTimeout(() => {
                window.location.href = window.location.pathname;
            }, 100);
        }

        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const selects = form.querySelectorAll('select');

            selects.forEach(select => {
                select.addEventListener('change', function() {
                    setNavigationFlag();
                    setTimeout(() => form.submit(), 100);
                });
            });

            // Submit on Enter key for search input
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        setNavigationFlag();
                        setTimeout(() => form.submit(), 100);
                    }
                });
            }

            // Set navigation flag for all links and buttons
            document.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                    setNavigationFlag();
                }
            });

            // Set navigation flag for form submissions
            document.addEventListener('submit', function(e) {
                setNavigationFlag();
            });
        });
    </script>


</x-dashboard-layout>


