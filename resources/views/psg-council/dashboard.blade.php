<x-dashboard-layout>
    <x-slot name="title">PSG Council Adviser Dashboard</x-slot>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-[#29553c] to-[#031a0a] bg-clip-text text-transparent">
                    PSG Council Adviser Dashboard
                </h1>
                <p class="text-gray-600 mt-2 text-lg">Welcome, PSG Council Adviser - Comprehensive Overview</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-[#29553c] to-[#031a0a] text-white px-6 py-3 rounded-xl shadow-lg">
                    <div class="text-sm font-medium">Today</div>
                    <div class="text-lg font-bold">{{ now()->format('M j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Pending Approvals -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl shadow-lg border border-orange-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-600 uppercase tracking-wide">Pending Approvals</p>
                    <p class="text-3xl font-bold text-orange-900 mt-2">{{ $pendingApprovals }}</p>
                    <p class="text-xs text-orange-700 mt-1">Awaiting your endorsement</p>
                </div>
                <div class="bg-orange-200 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved by Me -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">Approved by Me</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">{{ $approvedByMe }}</p>
                    <p class="text-xs text-green-700 mt-1">Successfully approved</p>
                </div>
                <div class="bg-green-200 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Organizations -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Total Organizations</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalOrganizations }}</p>
                    <p class="text-xs text-blue-700 mt-1">Registered clubs</p>
                </div>
                <div class="bg-blue-200 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Organizations -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg border border-purple-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Active Organizations</p>
                    <p class="text-3xl font-bold text-purple-900 mt-2">{{ $activeOrganizations }}</p>
                    <p class="text-xs text-purple-700 mt-1">Currently active</p>
                </div>
                <div class="bg-purple-200 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizations Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Organizations Overview</h2>
                    <p class="text-gray-600 mt-1">Manage and monitor all registered organizations</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="text-sm font-medium text-gray-600">Total: </span>
                        <span class="text-sm font-bold text-gray-900">{{ $clubs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('psg-council.dashboard') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search organizations..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    @foreach($clubTypes as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('psg-council.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Clear
                </a>
            </form>
        </div>

        <!-- Organizations Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($clubs as $club)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                        <!-- Club Header -->
                        <div class="p-4
                            @if($club->department === 'SASTE') bg-gradient-to-r from-blue-800 to-blue-900
                            @elseif($club->department === 'SBAHM') bg-gradient-to-r from-green-800 to-green-900
                            @elseif($club->department === 'SNAHS') bg-gradient-to-r from-red-800 to-red-900
                            @elseif($club->department === 'SITE') bg-gradient-to-r from-purple-800 to-purple-900
                            @elseif($club->department === 'BEU') bg-gradient-to-r from-yellow-700 to-yellow-800
                            @elseif(in_array($club->department, ['SOM', 'GRADUATE SCHOOL'])) bg-gradient-to-r from-slate-700 to-slate-800
                            @else bg-gradient-to-r from-slate-700 to-slate-800
                            @endif">
                            <h3 class="text-lg font-bold text-white truncate">{{ $club->name }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="
                                    @if($club->department === 'SASTE') text-blue-200
                                    @elseif($club->department === 'SBAHM') text-green-200
                                    @elseif($club->department === 'SNAHS') text-red-200
                                    @elseif($club->department === 'SITE') text-purple-200
                                    @elseif($club->department === 'BEU') text-yellow-200
                                    @elseif(in_array($club->department, ['SOM', 'GRADUATE SCHOOL'])) text-slate-200
                                    @else text-slate-200
                                    @endif text-sm">{{ $club->department }}</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($club->status === 'active') bg-green-100 text-green-800
                                    @elseif($club->status === 'suspended') bg-red-100 text-red-800
                                    @elseif($club->status === 'pending_renewal') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $club->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Club Details -->
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold
                                        @if($club->department === 'SASTE') text-blue-700
                                        @elseif($club->department === 'SBAHM') text-green-700
                                        @elseif($club->department === 'SNAHS') text-red-700
                                        @elseif($club->department === 'SITE') text-purple-700
                                        @elseif($club->department === 'BEU') text-yellow-700
                                        @elseif(in_array($club->department, ['SOM', 'GRADUATE SCHOOL'])) text-slate-700
                                        @else text-slate-700
                                        @endif">{{ $club->clubUsers->count() }}</div>
                                    <div class="text-xs text-gray-500">Members</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold
                                        @if($club->department === 'SASTE') text-blue-800
                                        @elseif($club->department === 'SBAHM') text-green-800
                                        @elseif($club->department === 'SNAHS') text-red-800
                                        @elseif($club->department === 'SITE') text-purple-800
                                        @elseif($club->department === 'BEU') text-yellow-800
                                        @elseif(in_array($club->department, ['SOM', 'GRADUATE SCHOOL'])) text-slate-800
                                        @else text-slate-800
                                        @endif">{{ $club->officers->count() }}</div>
                                    <div class="text-xs text-gray-500">Officers</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('psg-council.organization.show', ['club' => $club->id]) }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 rounded-lg transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No organizations found</h3>
                        <p class="mt-1 text-sm text-gray-500">No organizations match the current filters.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $clubs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>
