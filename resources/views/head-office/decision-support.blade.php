<x-dashboard-layout>
    <x-slot name="title">Club Violation Analysis - Decision Support System</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Club Violation Analysis</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor club violations and receive suspension recommendations</p>
    </div>

    <!-- Risk Level Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $riskCounts = [
                'none' => $clubsWithRisk->where('risk_level', 'none')->count(),
                'low' => $clubsWithRisk->where('risk_level', 'low')->count(),
                'high' => $clubsWithRisk->where('risk_level', 'high')->count(),
                'critical' => $clubsWithRisk->where('risk_level', 'critical')->count(),
            ];
        @endphp

        <div class="bg-green-50 rounded-xl shadow-sm border border-green-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 uppercase tracking-wide">NO RISK</p>
                    <p class="text-2xl font-bold text-green-900 mt-1">{{ $riskCounts['none'] }}</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">LOW RISK</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1">{{ $riskCounts['low'] }}</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 rounded-xl shadow-sm border border-orange-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 uppercase tracking-wide">HIGH RISK</p>
                    <p class="text-2xl font-bold text-orange-900 mt-1">{{ $riskCounts['high'] }}</p>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-red-50 rounded-xl shadow-sm border border-red-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600 uppercase tracking-wide">CRITICAL</p>
                    <p class="text-2xl font-bold text-red-900 mt-1">{{ $riskCounts['critical'] }}</p>
                </div>
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Clubs Risk Analysis Table -->
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">Club Risk Assessment & Recommendations</h2>
            <p class="text-sm text-gray-500 mt-1">Automated analysis based on violation history and patterns</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offense Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recommendation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clubsWithRisk as $club)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $club->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $club->department }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $riskColors = [
                                        'none' => 'bg-green-100 text-green-800',
                                        'low' => 'bg-blue-100 text-blue-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'critical' => 'bg-red-100 text-red-800'
                                    ];
                                    // Safety fallback for undefined risk levels
                                    $clubRiskColor = $riskColors[$club->risk_level] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $clubRiskColor }}">
                                    {{ ucfirst($club->risk_level ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    @if($club->offense_count === 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            No Offenses
                                        </span>
                                    @elseif($club->offense_count === 1)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <span class="font-bold mr-1">1st</span> Offense
                                        </span>
                                    @elseif($club->offense_count === 2)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            <span class="font-bold mr-1">2nd</span> Offense
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <span class="font-bold mr-1">3rd+</span> Offense ({{ $club->offense_count }})
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $club->violations->where('status', 'confirmed')->count() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate" title="{{ $club->recommendation }}">
                                    {{ $club->recommendation }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('head-office.decision-support.club-details', $club->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    View Details
                                </a>
                                @if($club->status === 'suspended')
                                    <button class="text-green-600 hover:text-green-900" 
                                            onclick="showReactivationModal('{{ $club->name }}', {{ $club->id }})">
                                        Reactivate
                                    </button>
                                @else
                                    @if($club->risk_level === 'critical' || $club->risk_level === 'high')
                                        <button class="text-red-600 hover:text-red-900" 
                                                onclick="showSuspensionModal('{{ $club->name }}', {{ $club->id }})">
                                            Suspend
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No clubs found with violation data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Appeals Section -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Pending Appeal Applications</h2>
                    <p class="text-sm text-gray-600 mt-1">Review and process appeals from clubs</p>
                </div>
                @if(isset($pendingAppeals) && $pendingAppeals->count() > 0)
                    <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-sm font-semibold">
                        {{ $pendingAppeals->count() }} Pending
                    </span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            @if(isset($pendingAppeals) && $pendingAppeals->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingAppeals as $appeal)
                            @php
                                // Count confirmed violations for this club
                                $confirmedCount = \App\Models\Violation::where('club_id', $appeal->club_id)
                                    ->where('status', 'confirmed')
                                    ->count();
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $appeal->club->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $appeal->club->department }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $appeal->violation->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @php
                                            $severityColors = [
                                                'minor' => 'bg-yellow-100 text-yellow-800',
                                                'moderate' => 'bg-orange-100 text-orange-800',
                                                'major' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $severityColors[$appeal->violation->severity] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($appeal->violation->severity) }}
                                        </span>
                                        <span class="ml-2">{{ $appeal->violation->points }} points</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $appeal->submitted_by }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appeal->submitted_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $appeal->submitted_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appeal->club->status === 'suspended')
                                        <div class="flex items-center">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Suspended
                                            </span>
                                        </div>
                                        @if($confirmedCount > 1)
                                            <div class="text-xs text-orange-600 mt-1 font-medium">
                                                ⚠️ {{ $confirmedCount }} violations remaining
                                            </div>
                                        @else
                                            <div class="text-xs text-green-600 mt-1 font-medium">
                                                ✓ Last violation
                                            </div>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        onclick="showAppealDetails({{ $appeal->id }})"
                                        class="text-blue-600 hover:text-blue-800 font-medium mr-3"
                                    >
                                        📄 More Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Appeals</h3>
                    <p class="text-gray-500">All appeals have been reviewed and processed.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Decision Support Legend -->
    <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Decision Support System Guidelines</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-4 border border-blue-100">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-700 font-bold text-sm">1st</span>
                    </div>
                    <h4 class="font-semibold text-blue-900">First Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-2"><strong>Action:</strong> Official Warning</p>
                <p class="text-xs text-gray-600">Club receives formal warning letter. Behavior is documented and monitored.</p>
            </div>
            <div class="bg-white rounded-lg p-4 border border-orange-100">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-orange-700 font-bold text-sm">2nd</span>
                    </div>
                    <h4 class="font-semibold text-orange-900">Second Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-2"><strong>Action:</strong> Temporary Suspension</p>
                <p class="text-xs text-gray-600">Club is temporarily suspended. Must submit improvement plan for reactivation.</p>
            </div>
            <div class="bg-white rounded-lg p-4 border border-red-100">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-red-700 font-bold text-sm">3rd</span>
                    </div>
                    <h4 class="font-semibold text-red-900">Third Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-2"><strong>Action:</strong> Club Termination</p>
                <p class="text-xs text-gray-600">Club registration is permanently revoked. All activities must cease immediately.</p>
            </div>
        </div>
        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
            <p class="text-xs text-blue-800">
                <strong>Note:</strong> Only confirmed violations count toward the offense system. Appeals must be resolved before counting. System provides recommendations; final decisions require administrative review.
            </p>
        </div>
    </div>

    <!-- Suspension Confirmation Modal -->
    <div id="suspensionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-[500px] shadow-xl rounded-lg bg-white">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">Suspend Organization</h3>
                    <p class="text-sm text-gray-500">This action requires administrative confirmation</p>
                </div>
            </div>
            
            <form id="suspensionForm" action="" method="POST" class="space-y-6">
                @csrf
                
                <!-- Organization Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2">
                        <span class="font-medium">Organization:</span> 
                        <span id="modalClubName" class="text-gray-900"></span>
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Risk Score:</span> 
                        <span id="modalRiskLevel" class="font-semibold"></span>
                    </p>
                </div>

                <!-- Admin Password -->
                <div class="space-y-2">
                    <label for="admin_password" class="block text-sm font-medium text-gray-700">
                        Administrator Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="admin_password" 
                        name="admin_password" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                        placeholder="Enter your administrator password"
                    >
                    <p class="text-xs text-gray-500">Required to verify your identity for this critical action</p>
                </div>

                <!-- Comprehensive Suspension Summary -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Suspension Information
                    </label>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="space-y-2 text-sm" id="suspensionSummary">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">This information will be recorded in the organization's violation history</p>
                    <input type="hidden" id="violation_reason" name="violation_reason" value="">
                    <input type="hidden" id="severity" name="severity" value="critical">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closeSuspensionModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors font-medium"
                    >
                        Confirm Suspension
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reactivation Confirmation Modal -->
    <div id="reactivationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-[500px] shadow-xl rounded-lg bg-white">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">Reactivate Organization</h3>
                    <p class="text-sm text-gray-500">This action requires administrative confirmation</p>
                </div>
            </div>
            
            <form id="reactivationForm" action="" method="POST" class="space-y-6">
                @csrf
                
                <!-- Organization Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2">
                        <span class="font-medium">Organization:</span> 
                        <span id="reactivationClubName" class="text-gray-900"></span>
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Current Status:</span> 
                        <span class="font-semibold text-red-600">Suspended</span>
                    </p>
                </div>

                <!-- Reactivation Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900 mb-1">Reactivation Notice</p>
                            <p class="text-xs text-blue-800">
                                This organization will be reactivated and restored to active status. All club activities and member access will be resumed immediately upon confirmation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Admin Password -->
                <div class="space-y-2">
                    <label for="reactivation_admin_password" class="block text-sm font-medium text-gray-700">
                        Administrator Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="reactivation_admin_password" 
                        name="admin_password" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        placeholder="Enter your administrator password"
                    >
                    <p class="text-xs text-gray-500">Required to verify your identity for this critical action</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closeReactivationModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors font-medium"
                    >
                        Confirm Reactivation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showSuspensionModal(clubName, clubId) {
            // Find the club data from the table to get risk score and violations
            const clubRow = document.querySelector(`button[onclick*="${clubId}"]`).closest('tr');
            const riskCell = clubRow.querySelector('td:nth-child(2)'); // Risk Level column
            const offenseCell = clubRow.querySelector('td:nth-child(3)'); // Offense Count column
            const violationsCell = clubRow.querySelector('td:nth-child(4)'); // Violations column
            
            const riskScore = riskCell.textContent.trim();
            const offenseCount = offenseCell.textContent.trim();
            const violationCount = violationsCell.textContent.trim();
            
            // Set club information in modal
            document.getElementById('modalClubName').textContent = clubName;
            document.getElementById('modalRiskLevel').textContent = offenseCount;
            document.getElementById('modalRiskLevel').className = `font-semibold ${getRiskLevelColor(riskScore)}`;
            
            // Auto-generate suspension reason and comprehensive summary
            let suspensionReason = '';
            let summaryHTML = '';
            
            if (riskScore.toLowerCase() === 'high') {
                suspensionReason = `Organization suspended due to HIGH RISK status: ${offenseCount}. Total confirmed violations: ${violationCount}. Action required: Temporary suspension per 2nd offense policy.`;
                summaryHTML = `
                    <div class="flex items-center mb-3 pb-3 border-b border-red-300">
                        <div class="w-3 h-3 rounded-full bg-orange-600 mr-2"></div>
                        <span class="font-semibold text-orange-800">HIGH RISK - ${offenseCount}</span>
                    </div>
                    <div class="space-y-1.5 text-gray-700">
                        <p><strong>Confirmed Violations:</strong> ${violationCount}</p>
                        <p><strong>Risk Status:</strong> HIGH - Second offense requiring temporary suspension</p>
                        <p><strong>Action:</strong> Temporary suspension per violation policy guidelines</p>
                    </div>
                `;
            } else if (riskScore.toLowerCase() === 'critical') {
                suspensionReason = `Organization suspended due to CRITICAL RISK status: ${offenseCount}. Total confirmed violations: ${violationCount}. Action required: Immediate suspension, potential termination review.`;
                summaryHTML = `
                    <div class="flex items-center mb-3 pb-3 border-b border-red-300">
                        <div class="w-3 h-3 rounded-full bg-red-600 mr-2"></div>
                        <span class="font-semibold text-red-800">CRITICAL RISK - ${offenseCount}</span>
                    </div>
                    <div class="space-y-1.5 text-gray-700">
                        <p><strong>Confirmed Violations:</strong> ${violationCount}</p>
                        <p><strong>Risk Status:</strong> CRITICAL - Severe violations requiring immediate action</p>
                        <p><strong>Action:</strong> Immediate suspension with termination review recommended</p>
                    </div>
                `;
            }
            
            document.getElementById('suspensionSummary').innerHTML = summaryHTML;
            document.getElementById('violation_reason').value = suspensionReason;
            
            // Set the form action
            const form = document.getElementById('suspensionForm');
            form.action = `/head-office/decision-support/suspend/${clubId}`;
            
            // Show the modal
            document.getElementById('suspensionModal').classList.remove('hidden');
        }

        function getRiskLevelColor(riskLevel) {
            switch(riskLevel.toLowerCase()) {
                case 'critical': return 'text-red-600';
                case 'high': return 'text-orange-600';
                case 'medium': return 'text-yellow-600';
                case 'low': return 'text-green-600';
                default: return 'text-gray-600';
            }
        }

        function closeSuspensionModal() {
            document.getElementById('suspensionModal').classList.add('hidden');
            // Clear form
            document.getElementById('suspensionForm').reset();
        }

        function showReactivationModal(clubName, clubId) {
            // Set club information in modal
            document.getElementById('reactivationClubName').textContent = clubName;
            
            // Set the form action
            const form = document.getElementById('reactivationForm');
            form.action = `/head-office/decision-support/reactivate/${clubId}`;
            
            // Show the modal
            document.getElementById('reactivationModal').classList.remove('hidden');
        }

        function closeReactivationModal() {
            document.getElementById('reactivationModal').classList.add('hidden');
            // Clear form
            document.getElementById('reactivationForm').reset();
        }

        // Handle form submission with AJAX for better UX
        document.getElementById('suspensionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    alertDiv.textContent = 'Organization suspended successfully!';
                    document.body.appendChild(alertDiv);
                    
                    // Remove alert after 3 seconds
                    setTimeout(() => {
                        document.body.removeChild(alertDiv);
                    }, 3000);
                    
                    closeSuspensionModal();
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Error processing suspension');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the suspension');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Confirm Suspension';
            });
        });

        // Handle reactivation form submission with AJAX
        document.getElementById('reactivationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    alertDiv.textContent = 'Organization reactivated successfully!';
                    document.body.appendChild(alertDiv);
                    
                    // Remove alert after 3 seconds
                    setTimeout(() => {
                        document.body.removeChild(alertDiv);
                    }, 3000);
                    
                    closeReactivationModal();
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Error processing reactivation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the reactivation');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Confirm Reactivation';
            });
        });
    </script>

    <!-- Appeal Details Modal -->
    <div id="appealModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-[700px] shadow-xl rounded-lg bg-white mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Appeal Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Review and process violation appeal</p>
                </div>
                <button onclick="closeAppealModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Appeal Content (loaded via AJAX) -->
            <div id="appealContent">
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600">Loading appeal details...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Appeal modal functions
        function showAppealDetails(appealId) {
            document.getElementById('appealModal').classList.remove('hidden');
            
            // Fetch appeal details
            fetch(`/head-office/decision-support/appeal/${appealId}`)
                .then(response => response.json())
                .then(data => {
                    const content = `
                        <!-- Club Information -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Club Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Club Name</span>
                                    <p class="text-sm font-medium text-gray-900">${data.club_name}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Department</span>
                                    <p class="text-sm font-medium text-gray-900">${data.department}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Violation Information -->
                        <div class="bg-red-50 rounded-lg p-4 mb-6 border border-red-200">
                            <h4 class="text-sm font-semibold text-red-900 mb-3">Violation Details</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-xs text-red-700">Title</span>
                                    <p class="text-sm font-medium text-red-900">${data.violation_title}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-red-700">Description</span>
                                    <p class="text-sm text-red-800">${data.violation_description}</p>
                                </div>
                                <div class="flex gap-4">
                                    <div>
                                        <span class="text-xs text-red-700">Date</span>
                                        <p class="text-sm text-red-900">${data.violation_date}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-red-700">Points</span>
                                        <p class="text-sm text-red-900">${data.violation_points}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-red-700">Severity</span>
                                        <p class="text-sm text-red-900">${data.violation_severity}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appellant Information -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                            <h4 class="text-sm font-semibold text-blue-900 mb-3">Appellant Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-blue-700">Name</span>
                                    <p class="text-sm font-medium text-blue-900">${data.submitted_by}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-blue-700">Position</span>
                                    <p class="text-sm font-medium text-blue-900">${data.position || 'N/A'}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-blue-700">Submission Date</span>
                                    <p class="text-sm text-blue-900">${data.submitted_at}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Appeal Description -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Appeal Description</h4>
                            <div class="bg-white border border-gray-300 rounded-lg p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">${data.appeal_reason}</p>
                            </div>
                        </div>

                        <!-- Supporting Document -->
                        ${data.has_attachment ? `
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Supporting Document</h4>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">${data.attachment_name}</p>
                                            <p class="text-xs text-gray-500">Click download to view</p>
                                        </div>
                                    </div>
                                    <a href="/head-office/decision-support/appeal/${data.appeal_id}/download" 
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Violations Status Indicator -->
                        ${data.club_status === 'suspended' ? `
                            <div class="mb-6">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-yellow-900 mb-1">Club Suspension Status</h4>
                                            <p class="text-sm text-yellow-800">
                                                <strong>Current Status:</strong> <span class="font-semibold">SUSPENDED</span>
                                            </p>
                                            <p class="text-sm text-yellow-800 mt-2">
                                                <strong>Confirmed Violations:</strong> ${data.confirmed_violations_count}
                                            </p>
                                            ${data.confirmed_violations_count > 1 ? `
                                                <p class="text-xs text-yellow-700 mt-2 italic">
                                                    ⚠️ This club has ${data.confirmed_violations_count} confirmed violation(s). All violations must be appealed and resolved before suspension can be lifted.
                                                </p>
                                            ` : `
                                                <p class="text-xs text-green-700 mt-2 italic">
                                                    ✓ This is the last confirmed violation. Accepting this appeal will lift the suspension.
                                                </p>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <button
                                onclick="closeAppealModal()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                onclick="rejectAppeal(${data.appeal_id})"
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                            >
                                ❌ Reject Appeal
                            </button>
                            <button
                                onclick="acceptAppeal(${data.appeal_id}, '${data.club_name}')"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                            >
                                ✅ Accept Appeal
                            </button>
                        </div>
                    `;
                    
                    document.getElementById('appealContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('appealContent').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-600">Error loading appeal details. Please try again.</p>
                        </div>
                    `;
                });
        }

        function closeAppealModal() {
            document.getElementById('appealModal').classList.add('hidden');
        }

        function acceptAppeal(appealId, clubName) {
            const confirmMsg = `Are you sure you want to accept this appeal for ${clubName}?\n\nNote: Suspension will only be lifted if ALL violations have been appealed.`;
            
            if (!confirm(confirmMsg)) {
                return;
            }

            fetch(`/head-office/decision-support/appeal/${appealId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let message = data.message;
                    
                    // Show detailed alert based on response
                    if (data.suspension_lifted) {
                        message += '\\n\\n✅ Club suspension has been LIFTED.';
                    } else if (data.remaining_violations > 0) {
                        message += `\\n\\n⚠️ ${data.remaining_violations} violation(s) still need to be appealed.`;
                    }
                    
                    alert(message);
                    closeAppealModal();
                    window.location.reload();
                } else {
                    alert(data.error || data.message || 'Error accepting appeal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while accepting the appeal. Please check the console for details.');
            });
        }

        function rejectAppeal(appealId) {
            const reason = prompt('Please provide a reason for rejecting this appeal:');
            if (!reason) return;

            fetch(`/head-office/decision-support/appeal/${appealId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appeal rejected successfully.');
                    closeAppealModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'Error rejecting appeal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the appeal');
            });
        }
    </script>
</x-dashboard-layout>
