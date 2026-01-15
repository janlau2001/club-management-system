<x-dashboard-layout>
    <x-slot name="title">Club Violation Analysis - Decision Support System</x-slot>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Club Violation Analysis</h1>
        <p class="text-gray-600 mt-2">Monitor club violations and receive suspension recommendations</p>
    </div>

    <!-- Risk Level Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @php
            $riskCounts = [
                'none' => $clubsWithRisk->where('risk_level', 'none')->count(),
                'low' => $clubsWithRisk->where('risk_level', 'low')->count(),
                'medium' => $clubsWithRisk->where('risk_level', 'medium')->count(),
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

        <div class="bg-yellow-50 rounded-xl shadow-sm border border-yellow-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600 uppercase tracking-wide">MEDIUM RISK</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $riskCounts['medium'] }}</p>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Club Risk Assessment & Recommendations</h2>
            <p class="text-sm text-gray-600 mt-1">Automated analysis based on violation history and patterns</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recent (6mo)</th>
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
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'critical' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $riskColors[$club->risk_level] }}">
                                    {{ ucfirst($club->risk_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="font-medium">{{ $club->risk_score }}</span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            @if($club->risk_score >= 100) bg-red-500
                                            @elseif($club->risk_score >= 50) bg-orange-500
                                            @elseif($club->risk_score >= 20) bg-yellow-500
                                            @elseif($club->risk_score > 0) bg-blue-500
                                            @else bg-green-500
                                            @endif" 
                                            style="width: {{ min($club->risk_score, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $club->violations_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $club->recent_violations }}
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

    <!-- Decision Support Legend -->
    <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Decision Support System Guidelines</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-blue-800 mb-2">Risk Score Calculation:</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Base score = Total violation points</li>
                    <li>• +20 points for 3+ violations in last 6 months</li>
                    <li>• +15 points for 5+ total violations</li>
                    <li>• Critical: 100+ points | High: 50-99 | Medium: 20-49 | Low: 1-19 | None: 0</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-blue-800 mb-2">Violation Point Values:</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Minor: 5-10 points | Moderate: 15-20 points</li>
                    <li>• Major: 25-35 points | Critical: 50+ points</li>
                    <li>• Points only count for confirmed violations</li>
                    <li>• System provides recommendations, final decisions require human review</li>
                </ul>
            </div>
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

                <!-- Suspension Reason -->
                <div class="space-y-2">
                    <label for="violation_reason" class="block text-sm font-medium text-gray-700">
                        Reason for Suspension <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="violation_reason" 
                        name="violation_reason" 
                        required 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                        placeholder="Provide detailed explanation for the suspension based on violations and policy breaches..."
                    ></textarea>
                    <p class="text-xs text-gray-500">This will be recorded in the organization's violation history</p>
                </div>

                <!-- Risk Level (Fixed as Critical) -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Risk Level
                    </label>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-md border">
                        <div class="w-3 h-3 rounded-full bg-red-600"></div>
                        <span class="font-medium text-red-800">Critical - Severe violations requiring immediate suspension</span>
                    </div>
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

                <!-- Reactivation Reason -->
                <div class="space-y-2">
                    <label for="reactivation_reason" class="block text-sm font-medium text-gray-700">
                        Reason for Reactivation <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="reactivation_reason" 
                        name="reactivation_reason" 
                        required 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                        placeholder="Provide detailed explanation for reactivating this organization (e.g., issues resolved, compliance measures implemented)..."
                    ></textarea>
                    <p class="text-xs text-gray-500">This will be recorded in the organization's activity history</p>
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
            // Find the club data from the table to get risk score
            const clubRow = document.querySelector(`button[onclick*="${clubId}"]`).closest('tr');
            const riskCell = clubRow.querySelector('td:nth-child(3)'); // Risk Level column
            
            const riskScore = riskCell.textContent.trim();
            
            // Set club information in modal
            document.getElementById('modalClubName').textContent = clubName;
            document.getElementById('modalRiskLevel').textContent = riskScore;
            document.getElementById('modalRiskLevel').className = `font-semibold ${getRiskLevelColor(riskScore)}`;
            
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
</x-dashboard-layout>
