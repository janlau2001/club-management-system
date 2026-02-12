<x-dashboard-layout>
    <x-slot name="title">{{ $club->name }} - Violation Details</x-slot>

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $club->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Detailed violation history and risk assessment</p>
            </div>
            <a href="{{ route('head-office.decision-support') }}" 
               class="px-4 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                &larr; Back to Analysis
            </a>
        </div>
    </div>

    <!-- Club Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-gray-200 p-6">
            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-4">Club Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Department:</span>
                    <p class="font-medium text-gray-900">{{ $club->department }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Type:</span>
                    <p class="font-medium text-gray-900">{{ $club->club_type }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Members:</span>
                    <p class="font-medium text-gray-900">{{ $club->member_count }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-medium 
                        {{ $club->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($club->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-6">
            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-4">Risk Assessment</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Confirmed Violations:</span>
                    <p class="text-2xl font-semibold text-gray-900">{{ $club->violations->where('status', 'confirmed')->count() }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Risk Level:</span>
                    @php
                        $riskColors = [
                            'none' => 'bg-green-100 text-green-800',
                            'low' => 'bg-blue-100 text-blue-800',
                            'high' => 'bg-orange-100 text-orange-800',
                            'critical' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-medium {{ $riskColors[$club->risk_level] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($club->risk_level) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Total Violations:</span>
                    <p class="font-medium text-gray-900">{{ $club->violations->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-6">
            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-4">Club Officers</h3>
            <div class="space-y-3">
                @forelse($club->clubUsers->where('role', 'officer') as $officer)
                    <div>
                        <p class="font-medium text-gray-900">{{ $officer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $officer->position ?? 'Officer' }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No officers listed</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Violation Timeline -->
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">Violation History</h2>
            <p class="text-sm text-gray-500 mt-1">Chronological record of all violations</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($club->violations as $violation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $violation->violation_date->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $violation->title }}</div>
                                <div class="text-sm text-gray-500 max-w-xs">{{ Str::limit($violation->description, 100) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($violation->violation_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium {{ $violation->severity_color }}">
                                    {{ ucfirst($violation->severity) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium {{ $violation->status_color }}">
                                    {{ ucfirst($violation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $violation->reported_by }}
                            </td>
                        </tr>
                        @if($violation->description && strlen($violation->description) > 100)
                        <tr>
                            <td colspan="6" class="px-6 py-2 bg-gray-50 text-sm text-gray-700">
                                <strong>Full Description:</strong> {{ $violation->description }}
                                @if($violation->evidence)
                                    <br><strong>Evidence:</strong> {{ $violation->evidence }}
                                @endif
                                @if($violation->resolution_notes)
                                    <br><strong>Resolution:</strong> {{ $violation->resolution_notes }}
                                @endif
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No violations recorded for this club.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Violation Pattern Analysis -->
    @if($club->violations->count() > 0)
    <div class="mt-8 bg-white border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Pattern Analysis</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Violation Types</h4>
                @php
                    $violationTypes = $club->violations->groupBy('violation_type');
                @endphp
                <div class="space-y-2">
                    @foreach($violationTypes as $type => $violations)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ ucfirst($type) }}</span>
                            <span class="font-medium text-gray-900">{{ $violations->count() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div>
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Severity Distribution</h4>
                @php
                    $severityTypes = $club->violations->groupBy('severity');
                @endphp
                <div class="space-y-2">
                    @foreach($severityTypes as $severity => $violations)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ ucfirst($severity) }}</span>
                            <span class="font-medium text-gray-900">{{ $violations->count() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div>
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Recent Activity</h4>
                @php
                    $recentViolations = $club->violations->where('violation_date', '>=', now()->subMonths(6));
                    $oldViolations = $club->violations->where('violation_date', '<', now()->subMonths(6));
                @endphp
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Last 6 months</span>
                        <span class="font-medium text-gray-900">{{ $recentViolations->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Older violations</span>
                        <span class="font-medium text-gray-900">{{ $oldViolations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recommended Actions -->
    <div class="mt-8 bg-white border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">System Recommendation</h3>
        <div class="border border-gray-200 p-4">
            <p class="text-gray-800">{{ $club->recommendation }}</p>
            
            @if($club->risk_level === 'critical')
                <div class="mt-4 p-4 border border-red-200 bg-red-50">
                    <p class="text-red-800 font-medium">Critical Risk Level Detected</p>
                    <p class="text-red-700 text-sm mt-1">
                        This club has {{ $club->offense_count }} confirmed violations (3rd+ offense). 
                        Consider immediate intervention and formal review procedures.
                    </p>
                </div>
            @elseif($club->risk_level === 'high')
                <div class="mt-4 p-4 border border-orange-200 bg-orange-50">
                    <p class="text-orange-800 font-medium">High Risk Level</p>
                    <p class="text-orange-700 text-sm mt-1">
                        This club requires close monitoring and may need corrective action plans.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
