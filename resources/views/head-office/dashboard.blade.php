<x-dashboard-layout>
    <x-slot name="title">SAASS Dashboard</x-slot>

    <div class="pb-12 space-y-8">

        {{-- ╔══════════════════════════════════════════════╗
            ║  BANNER — Greeting + Actionable summary     ║
            ╚══════════════════════════════════════════════╝ --}}
        <div class="bg-gray-900 text-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, SAASS</h1>
                <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, F d, Y') }} &mdash; Here's your system overview</p>
            </div>
            @if($actionableItems > 0)
                <div class="flex items-center gap-3 bg-white/10 px-4 py-2.5">
                    <div class="w-8 h-8 bg-yellow-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-gray-900">{{ $actionableItems }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Items need your action</p>
                        <p class="text-xs text-gray-400">
                            @if($newRegistrations > 0){{ $newRegistrations }} registration{{ $newRegistrations > 1 ? 's' : '' }}@endif
                            @if($newRegistrations > 0 && ($pendingAppeals > 0 || $pendingRenewals > 0)), @endif
                            @if($pendingAppeals > 0){{ $pendingAppeals }} appeal{{ $pendingAppeals > 1 ? 's' : '' }}@endif
                            @if($pendingAppeals > 0 && $pendingRenewals > 0), @endif
                            @if($pendingRenewals > 0){{ $pendingRenewals }} renewal{{ $pendingRenewals > 1 ? 's' : '' }}@endif
                        </p>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 bg-white/10 px-4 py-2.5">
                    <div class="w-8 h-8 bg-green-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">All caught up</p>
                        <p class="text-xs text-gray-400">No pending actions at this time</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ╔══════════════════════════════════════════════╗
            ║  SYSTEM PULSE — Horizontal status strip     ║
            ╚══════════════════════════════════════════════╝ --}}
        <div class="bg-white border border-gray-200">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">System Pulse</h2>
            </div>

            {{-- Segmented status bar --}}
            @php
                $segments = [
                    ['label' => 'Active', 'count' => $activeOrganizations, 'color' => 'bg-green-500', 'pct' => $totalOrganizations > 0 ? round(($activeOrganizations / $totalOrganizations) * 100) : 0],
                    ['label' => 'Pending Renewal', 'count' => $pendingRenewalOrganizations, 'color' => 'bg-yellow-500', 'pct' => $totalOrganizations > 0 ? round(($pendingRenewalOrganizations / $totalOrganizations) * 100) : 0],
                    ['label' => 'Suspended', 'count' => $suspendedOrganizations, 'color' => 'bg-red-500', 'pct' => $totalOrganizations > 0 ? round(($suspendedOrganizations / $totalOrganizations) * 100) : 0],
                ];
            @endphp
            <div class="px-5 pt-4 pb-2">
                <div class="flex items-baseline justify-between mb-2">
                    <span class="text-sm text-gray-600">{{ $totalOrganizations }} Total Organizations</span>
                    <span class="text-sm text-gray-600">{{ number_format($totalMembers) }} Total Members</span>
                </div>
                <div class="flex h-3 w-full overflow-hidden bg-gray-100">
                    @foreach($segments as $seg)
                        @if($seg['pct'] > 0)
                            <div class="{{ $seg['color'] }}" style="width: {{ $seg['pct'] }}%" title="{{ $seg['label'] }}: {{ $seg['count'] }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-3 divide-x divide-gray-200 border-t border-gray-200">
                @foreach($segments as $seg)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <span class="w-2.5 h-2.5 flex-shrink-0 {{ $seg['color'] }}"></span>
                        <div>
                            <span class="text-lg font-bold text-gray-900">{{ $seg['count'] }}</span>
                            <span class="text-xs text-gray-500 ml-1">{{ $seg['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ╔══════════════════════════════════════════════╗
            ║  ACTION CENTER — Tasks requiring attention   ║
            ╚══════════════════════════════════════════════╝ --}}
        <div class="bg-white border border-gray-200">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Action Center</h2>
                <span class="text-xs text-gray-400">Tasks requiring your attention</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                {{-- Registrations --}}
                <a href="{{ route('head-office.approvals') }}" class="group p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Registrations</span>
                        </div>
                        @if($newRegistrations > 0)
                            <span class="w-6 h-6 bg-blue-600 text-white text-xs font-bold flex items-center justify-center">{{ $newRegistrations }}</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $newRegistrations }}</p>
                    <p class="text-xs text-gray-500 mt-1">pending verification</p>
                    <div class="mt-3 flex gap-4 text-[11px] text-gray-400">
                        <span>{{ $approvedRegistrations }} approved</span>
                        <span>{{ $rejectedRegistrations }} rejected</span>
                    </div>
                </a>

                {{-- Renewals --}}
                <a href="{{ route('head-office.renewals') }}" class="group p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-yellow-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Renewals</span>
                        </div>
                        @if($pendingRenewals > 0)
                            <span class="w-6 h-6 bg-yellow-600 text-white text-xs font-bold flex items-center justify-center">{{ $pendingRenewals }}</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingRenewals }}</p>
                    <p class="text-xs text-gray-500 mt-1">awaiting review</p>
                    <div class="mt-3 flex gap-4 text-[11px] text-gray-400">
                        <span>{{ $approvedRenewals }} approved</span>
                        <span>{{ $pendingRenewalOrganizations }} clubs pending</span>
                    </div>
                </a>

                {{-- Appeals --}}
                <a href="{{ route('head-office.decision-support.appeals') }}" class="group p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-red-100 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Appeals</span>
                        </div>
                        @if($pendingAppeals > 0)
                            <span class="w-6 h-6 bg-red-600 text-white text-xs font-bold flex items-center justify-center">{{ $pendingAppeals }}</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingAppeals }}</p>
                    <p class="text-xs text-gray-500 mt-1">need decision</p>
                    <div class="mt-3 flex gap-4 text-[11px] text-gray-400">
                        <span>{{ $approvedAppeals }} accepted</span>
                        <span>{{ $rejectedAppeals }} rejected</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- ╔══════════════════════════════════════════════╗
            ║  MAIN GRID — 8-col / 4-col split            ║
            ╚══════════════════════════════════════════════╝ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ── LEFT COLUMN (8/12) ───────────────── --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Club Directory (compact table) --}}
                <div class="bg-white border border-gray-200">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Club Directory</h2>
                        <a href="{{ route('head-office.organizations') }}" class="text-xs text-gray-500 hover:text-gray-900 underline">View all organizations →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left">
                                    <th class="px-5 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Club Name</th>
                                    <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Department</th>
                                    <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Type</th>
                                    <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide text-center">Members</th>
                                    <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide text-center">Violations</th>
                                    <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($allClubs->take(8) as $club)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-2.5">
                                            <a href="{{ route('head-office.organization.show', $club) }}" class="font-medium text-gray-900 hover:underline">{{ $club->name }}</a>
                                        </td>
                                        <td class="px-3 py-2.5 text-gray-500">{{ $club->department }}</td>
                                        <td class="px-3 py-2.5">
                                            <span class="text-xs text-gray-500">{{ $club->club_type }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 text-center text-gray-700">{{ $club->member_count }}</td>
                                        <td class="px-3 py-2.5 text-center">
                                            @if($club->confirmed_violations_count > 0)
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-700 text-[11px] font-bold">{{ $club->confirmed_violations_count }}</span>
                                            @elseif($club->total_violations_count > 0)
                                                <span class="text-gray-400 text-xs">{{ $club->total_violations_count }}</span>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2.5">
                                            @php
                                                $statusMap = [
                                                    'active' => ['label' => 'Active', 'class' => 'bg-green-100 text-green-700 border-green-200'],
                                                    'suspended' => ['label' => 'Suspended', 'class' => 'bg-red-100 text-red-700 border-red-200'],
                                                    'pending_renewal' => ['label' => 'Renewal', 'class' => 'bg-yellow-100 text-yellow-700 border-yellow-200'],
                                                    'pending' => ['label' => 'Pending', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                                                ];
                                                $s = $statusMap[$club->status] ?? ['label' => ucfirst($club->status), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
                                            @endphp
                                            <span class="px-2 py-0.5 text-[11px] font-medium border {{ $s['class'] }}">{{ $s['label'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">No clubs registered yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($allClubs->count() > 8)
                        <div class="px-5 py-3 border-t border-gray-200 bg-gray-50">
                            <a href="{{ route('head-office.organizations') }}" class="text-xs text-gray-600 hover:text-gray-900">+ {{ $allClubs->count() - 8 }} more organizations</a>
                        </div>
                    @endif
                </div>

                {{-- Flagged Clubs + Pending Registrations side-by-side --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Flagged --}}
                    <div class="bg-white border border-gray-200">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Flagged Clubs</h3>
                        </div>
                        @if($clubsNeedingAttention->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($clubsNeedingAttention as $club)
                                    <a href="{{ route('head-office.organization.show', $club) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $club->name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $club->department }}</p>
                                        </div>
                                        <div class="flex gap-1.5 ml-3 flex-shrink-0">
                                            @if($club->status === 'suspended')
                                                <span class="w-2 h-2 bg-red-500" title="Suspended"></span>
                                            @endif
                                            @if($club->confirmed_violations_count > 0)
                                                <span class="w-2 h-2 bg-orange-500" title="{{ $club->confirmed_violations_count }} violation(s)"></span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="px-5 py-6 text-center">
                                <p class="text-xs text-gray-400">No flagged clubs</p>
                            </div>
                        @endif
                    </div>

                    {{-- Pending Registrations --}}
                    <div class="bg-white border border-gray-200">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Pending Registrations</h3>
                        </div>
                        @if($pendingRegistrations->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($pendingRegistrations as $reg)
                                    <a href="{{ route('head-office.approvals.show', $reg) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $reg->club_name }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $reg->department }} · {{ $reg->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$reg->verified_by_osa)
                                            <span class="w-2 h-2 bg-blue-500 ml-3 flex-shrink-0" title="Unverified"></span>
                                        @else
                                            <span class="w-2 h-2 bg-green-500 ml-3 flex-shrink-0" title="Verified"></span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="px-5 py-6 text-center">
                                <p class="text-xs text-gray-400">No pending registrations</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pending Appeals --}}
                @if($recentAppeals->count() > 0)
                    <div class="bg-white border border-gray-200">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Appeals Awaiting Decision</h3>
                            <a href="{{ route('head-office.decision-support.appeals') }}" class="text-xs text-gray-500 hover:text-gray-900 underline">View all →</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($recentAppeals as $appeal)
                                <a href="{{ route('head-office.decision-support.appeal-details', $appeal) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $appeal->violation ? $appeal->violation->title : 'Unknown Violation' }}</p>
                                        <p class="text-[11px] text-gray-400">{{ $appeal->club ? $appeal->club->name : 'Unknown' }} · {{ $appeal->submitted_at ? \Carbon\Carbon::parse($appeal->submitted_at)->diffForHumans() : '' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 text-[11px] font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 flex-shrink-0">Pending</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── RIGHT COLUMN (4/12) ──────────────── --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Quick Navigate --}}
                <div class="bg-white border border-gray-200">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Navigate</h3>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('head-office.approvals') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm text-gray-700">Registration Monitoring</span>
                            @if($newRegistrations > 0)<span class="ml-auto text-[11px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5">{{ $newRegistrations }}</span>@endif
                        </a>
                        <a href="{{ route('head-office.renewals') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="text-sm text-gray-700">Renewals</span>
                            @if($pendingRenewals > 0)<span class="ml-auto text-[11px] font-bold text-yellow-700 bg-yellow-100 px-1.5 py-0.5">{{ $pendingRenewals }}</span>@endif
                        </a>
                        <a href="{{ route('head-office.organizations') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-sm text-gray-700">Organizations</span>
                        </a>
                        <a href="{{ route('head-office.members') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm text-gray-700">Members</span>
                        </a>
                        <a href="{{ route('head-office.decision-support') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="text-sm text-gray-700">Violation Analysis</span>
                            @if($pendingAppeals > 0)<span class="ml-auto text-[11px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5">{{ $pendingAppeals }}</span>@endif
                        </a>
                        <a href="{{ route('head-office.reports') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm text-gray-700">Reports</span>
                        </a>
                    </div>
                </div>

                {{-- Violations Insight --}}
                <div class="bg-white border border-gray-200">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Violations Insight</h3>
                    </div>
                    <div class="px-5 py-4">
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-3xl font-bold text-gray-900">{{ $totalViolations }}</span>
                            <span class="text-xs text-gray-500">total violations</span>
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2 mb-5">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest">By Status</p>
                            @php
                                $vStatuses = [
                                    ['label' => 'Confirmed', 'count' => $confirmedViolations, 'color' => 'bg-red-500'],
                                    ['label' => 'Appealed', 'count' => $appealedViolations, 'color' => 'bg-yellow-500'],
                                    ['label' => 'Dismissed', 'count' => $dismissedViolations, 'color' => 'bg-green-500'],
                                ];
                            @endphp
                            @foreach($vStatuses as $vs)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 {{ $vs['color'] }}"></span>
                                        <span class="text-sm text-gray-600">{{ $vs['label'] }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $vs['count'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Severity --}}
                        <div class="space-y-2 pt-4 border-t border-gray-100">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest">By Severity</p>
                            @php
                                $severities = [
                                    ['label' => 'Major', 'count' => $majorViolations, 'color' => 'bg-red-500'],
                                    ['label' => 'Moderate', 'count' => $moderateViolations, 'color' => 'bg-orange-400'],
                                    ['label' => 'Minor', 'count' => $minorViolations, 'color' => 'bg-yellow-400'],
                                ];
                            @endphp
                            @if($totalViolations > 0)
                                <div class="flex h-2 w-full overflow-hidden bg-gray-100">
                                    @foreach($severities as $sev)
                                        @if($sev['count'] > 0)
                                            <div class="{{ $sev['color'] }}" style="width: {{ round(($sev['count'] / $totalViolations) * 100) }}%"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @foreach($severities as $sev)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 {{ $sev['color'] }}"></span>
                                        <span class="text-sm text-gray-600">{{ $sev['label'] }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $sev['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Department Breakdown --}}
                <div class="bg-white border border-gray-200">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Departments</h3>
                    </div>
                    @if($departmentDistribution->count() > 0)
                        <div class="px-5 py-4 space-y-3">
                            @foreach($departmentDistribution as $dept)
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm text-gray-700 truncate pr-2">{{ $dept->department ?? 'Unassigned' }}</span>
                                        <span class="text-xs font-semibold text-gray-900 tabular-nums">{{ $dept->count }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-1">
                                        <div class="bg-gray-900 h-1 transition-all" style="width: {{ $totalOrganizations > 0 ? round(($dept->count / $totalOrganizations) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-6 text-center">
                            <p class="text-xs text-gray-400">No data</p>
                        </div>
                    @endif
                </div>

                {{-- Organization Types --}}
                <div class="bg-white border border-gray-200">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Club Types</h3>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-gray-200">
                        <div class="p-4 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $academicClubs }}</p>
                            <p class="text-[11px] text-gray-500 mt-1">Academic</p>
                        </div>
                        <div class="p-4 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $interestClubs }}</p>
                            <p class="text-[11px] text-gray-500 mt-1">Interest</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ╔══════════════════════════════════════════════╗
            ║  ACTIVITY FEED — Full-width timeline         ║
            ╚══════════════════════════════════════════════╝ --}}
        <div class="bg-white border border-gray-200">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Recent Activity</h2>
                <span class="text-[11px] text-gray-400">Latest {{ $recentActivities->count() }} events</span>
            </div>
            @if($recentActivities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                    @php
                        $half = ceil($recentActivities->count() / 2);
                        $leftCol = $recentActivities->take($half);
                        $rightCol = $recentActivities->skip($half);
                    @endphp
                    <div class="divide-y divide-gray-100">
                        @foreach($leftCol as $activity)
                            <div class="flex items-start gap-3 px-5 py-3">
                                @php
                                    $dotColor = match($activity['color']) {
                                        'green' => 'bg-green-500',
                                        'red' => 'bg-red-500',
                                        'blue' => 'bg-blue-500',
                                        'yellow' => 'bg-yellow-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <span class="w-1.5 h-1.5 mt-2 flex-shrink-0 {{ $dotColor }}"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 truncate">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $activity['description'] }}</p>
                                </div>
                                <span class="text-[11px] text-gray-400 flex-shrink-0 whitespace-nowrap mt-0.5">{{ $activity['time']->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($rightCol as $activity)
                            <div class="flex items-start gap-3 px-5 py-3">
                                @php
                                    $dotColor = match($activity['color']) {
                                        'green' => 'bg-green-500',
                                        'red' => 'bg-red-500',
                                        'blue' => 'bg-blue-500',
                                        'yellow' => 'bg-yellow-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <span class="w-1.5 h-1.5 mt-2 flex-shrink-0 {{ $dotColor }}"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 truncate">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $activity['description'] }}</p>
                                </div>
                                <span class="text-[11px] text-gray-400 flex-shrink-0 whitespace-nowrap mt-0.5">{{ $activity['time']->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="px-5 py-8 text-center">
                    <p class="text-sm text-gray-400">No recent activity</p>
                </div>
            @endif
        </div>

    </div>
</x-dashboard-layout>
