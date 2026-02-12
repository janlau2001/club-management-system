<x-dashboard-layout>
    <x-slot name="title">Organizations</x-slot>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Organizations Management
                </h1>
                <p class="text-sm text-gray-500 mt-1">Comprehensive oversight and management of all student organizations</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-gray-900 text-white px-6 py-3">
                    <div class="text-xs font-medium uppercase tracking-wide">Total Organizations</div>
                    <div class="text-lg font-semibold">{{ $clubs->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizations Section -->
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Student Organizations</h2>
                    <p class="text-sm text-gray-500 mt-1">Complete management and oversight of all registered organizations</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-white px-4 py-2 border border-gray-200">
                        <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Active: </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $clubs->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="bg-white px-4 py-2 border border-gray-200">
                        <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Suspended: </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $clubs->where('status', 'suspended')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('head-office.organizations') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search organizations..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                </div>
                <select name="type" class="px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                    <option value="">All Types</option>
                    @foreach($clubTypes as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                <select name="department" class="px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-900 transition-colors">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>{{ $department }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2 text-sm font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('head-office.organizations') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 text-sm font-medium transition-colors">
                    Clear
                </a>
            </form>
        </div>

        <!-- Organizations Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($clubs as $club)
                    <div class="bg-white border border-gray-200 hover:border-gray-400 transition-colors overflow-hidden">
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

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Type:</span>
                                    <span class="font-medium">{{ $club->club_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Registered:</span>
                                    <span class="font-medium">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Adviser:</span>
                                    <span class="font-medium truncate ml-2">{{ $club->adviser_name }}</span>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex space-x-2">
                                    <a href="{{ route('head-office.organization.show', $club) }}"
                                       class="flex-1 px-3 py-2 rounded-lg text-xs font-medium text-center transition-colors
                                        @if($club->department === 'SASTE') bg-blue-100 hover:bg-blue-200 text-blue-800
                                        @elseif($club->department === 'SBAHM') bg-green-100 hover:bg-green-200 text-green-800
                                        @elseif($club->department === 'SNAHS') bg-red-100 hover:bg-red-200 text-red-800
                                        @elseif($club->department === 'SITE') bg-purple-100 hover:bg-purple-200 text-purple-800
                                        @elseif($club->department === 'BEU') bg-yellow-100 hover:bg-yellow-200 text-yellow-800
                                        @elseif(in_array($club->department, ['SOM', 'GRADUATE SCHOOL'])) bg-slate-100 hover:bg-slate-200 text-slate-800
                                        @else bg-slate-100 hover:bg-slate-200 text-slate-800
                                        @endif">
                                        View Details
                                    </a>
                                    @if($club->status === 'suspended')
                                        <span class="flex-1 bg-red-50 text-red-700 px-3 py-2 rounded-lg text-xs font-medium text-center">
                                            Suspended
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No organizations found</h3>
                        <p class="text-gray-500">No organizations match your current filter criteria.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


</x-dashboard-layout>
