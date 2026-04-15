<x-dashboard-layout>
    <x-slot name="title">{{ $club->name }}</x-slot>

    @php
        $officers    = $club->clubUsers->where('role', 'officer');
        $advisers    = $club->clubUsers->where('role', 'adviser');
        $members     = $club->clubUsers->where('role', 'member');
        $totalUsers  = max($club->clubUsers->count(), 1);
        $onlineCount = $club->clubUsers->filter(fn($u) => $u->isOnline())->count();
    @endphp

    <div class="space-y-5">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $club->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $club->department }} &middot; {{ $club->club_type }}</p>
            </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('head-office.reports.single-club', $club->id) }}"
               class="inline-flex items-center gap-1.5 bg-[#29553c] hover:bg-[#1e3d2c] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-file-pdf text-xs"></i> Export PDF
            </a>
            <a href="{{ route('head-office.organizations') }}" class="text-sm text-blue-600 hover:text-blue-800">← Back to Organizations</a>
        </div>
        </div>

        <!-- ── ROW 1: Club Info (left 2/3) + Quick Stats sidebar (right 1/3) ── -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Club Information -->
            <div class="lg:col-span-2 bg-white border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Club Information</h2>
                </div>
                <div class="p-5 grid grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Department</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $club->department }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Club Type</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $club->club_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                        <div class="mt-1">
                            <span class="px-2 py-0.5 text-xs font-medium {{ $club->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($club->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date Registered</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Faculty Adviser</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $club->adviser_name ?: 'Not assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Members</p>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $club->clubUsers->count() }}</p>
                        <p class="text-xs text-gray-400">{{ $officers->count() }} officers &middot; {{ $advisers->count() }} advisers &middot; {{ $members->count() }} members</p>
                    </div>
                    @if($club->description)
                        <div class="col-span-2">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</p>
                            <p class="mt-1 text-sm text-gray-900 leading-relaxed">{{ $club->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats Sidebar -->
            <div class="flex flex-col gap-4">

                <!-- Membership Breakdown -->
                <div class="bg-white border border-gray-200 p-5 flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Membership Breakdown</p>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Officers</span>
                                <span class="font-semibold text-gray-900">{{ $officers->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5">
                                <div class="bg-gray-900 h-1.5" style="width: {{ round(($officers->count() / $totalUsers) * 100) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Advisers</span>
                                <span class="font-semibold text-gray-900">{{ $advisers->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5">
                                <div class="bg-[#29553c] h-1.5" style="width: {{ round(($advisers->count() / $totalUsers) * 100) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Members</span>
                                <span class="font-semibold text-gray-900">{{ $members->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5">
                                <div class="bg-blue-500 h-1.5" style="width: {{ round(($members->count() / $totalUsers) * 100) }}%"></div>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Total</span>
                            <span class="font-bold text-gray-900">{{ $club->clubUsers->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Currently Online -->
                <div class="bg-white border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Currently Online</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-gray-900">{{ $onlineCount }}</span>
                        <span class="text-sm text-gray-400 mb-0.5">/ {{ $club->clubUsers->count() }}</span>
                    </div>
                    @if($onlineCount > 0)
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-xs text-green-700">{{ $onlineCount }} active now</span>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 mt-1">No members online</p>
                    @endif
                </div>

            </div>
        </div>

        <!-- ── ROW 2: Officers (left) + Advisers (right) ── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- Officers Panel -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Club Officers</h2>
                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-0.5 font-medium">{{ $officers->count() }}</span>
                </div>
                @if($officers->isEmpty())
                    <p class="p-5 text-sm text-gray-400 italic">No officers assigned.</p>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($officers as $officer)
                            <div class="px-5 py-4 flex items-start gap-3">
                                <div class="w-9 h-9 bg-gray-900 text-white flex items-center justify-center text-sm font-bold shrink-0 select-none">
                                    {{ strtoupper(substr($officer->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $officer->name }}</p>
                                        @if($officer->isOnline())
                                            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0" title="Online"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600">{{ $officer->position ?? 'Officer' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $officer->email }}</p>
                                    <div class="flex flex-wrap items-center gap-x-3 mt-1 text-xs text-gray-400">
                                        @if($officer->student_id)<span>{{ $officer->student_id }}</span>@endif
                                        <span>{{ $officer->department }}</span>
                                        @if($officer->year_level)<span>{{ $officer->year_level }}</span>@endif
                                        <span>Joined {{ $officer->joined_date ? $officer->joined_date->format('M j, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Advisers Panel -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Club Advisers</h2>
                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-0.5 font-medium">{{ $advisers->count() }}</span>
                </div>
                @if($advisers->isEmpty())
                    <p class="p-5 text-sm text-gray-400 italic">No advisers assigned.</p>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($advisers as $adviser)
                            <div class="px-5 py-4 flex items-start gap-3">
                                <div class="w-9 h-9 bg-[#29553c] text-white flex items-center justify-center text-sm font-bold shrink-0 select-none">
                                    {{ strtoupper(substr($adviser->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $adviser->name }}</p>
                                        @if($adviser->isOnline())
                                            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0" title="Online"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600">{{ $adviser->position ?? 'Club Adviser' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $adviser->email }}</p>
                                    <div class="flex flex-wrap items-center gap-x-3 mt-1 text-xs text-gray-400">
                                        @if($adviser->professor_id)<span>ID: {{ $adviser->professor_id }}</span>@endif
                                        @if($adviser->department_office)<span>{{ $adviser->department_office }}</span>@endif
                                        <span>Joined {{ $adviser->joined_date ? $adviser->joined_date->format('M j, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- ── ROW 3: Members Table (full width, scrollable body) ── -->
        @if($members->count() > 0)
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Club Members</h2>
                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-0.5 font-medium">{{ $members->count() }}</span>
                </div>
                <div class="overflow-x-auto">
                    <div class="max-h-72 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($members as $member)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $member->name }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->email }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->student_id ?? 'N/A' }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->department }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->year_level }}</td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @if($member->isOnline())
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-dashboard-layout>
