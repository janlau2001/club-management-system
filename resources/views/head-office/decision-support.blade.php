<x-dashboard-layout>
    <x-slot name="title">Club Violation Analysis - Decision Support System</x-slot>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Club Violation Analysis</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor club violations and manage suspension recommendations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('head-office.decision-support.appeals') }}" 
               class="px-4 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                View Appeals
            </a>
            <button onclick="openRecordViolationModal()" 
                    class="px-4 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">
                Record Violation
            </button>
        </div>
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

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">NO RISK</p>
                </div>
                <p class="text-3xl font-semibold text-gray-900">{{ $riskCounts['none'] }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">LOW RISK</p>
                </div>
                <p class="text-3xl font-semibold text-gray-900">{{ $riskCounts['low'] }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">HIGH RISK</p>
                </div>
                <p class="text-3xl font-semibold text-gray-900">{{ $riskCounts['high'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Auto-suspended</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 p-5">
            <div class="flex flex-col">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">CRITICAL</p>
                </div>
                <p class="text-3xl font-semibold text-gray-900">{{ $riskCounts['critical'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Auto-suspended</p>
            </div>
        </div>
    </div>

    <!-- Clubs Risk Analysis Table -->
    <div class="bg-white border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">Club Risk Assessment</h2>
            <p class="text-sm text-gray-500 mt-1">Clubs are automatically suspended upon reaching 2 confirmed violations</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offense Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recommendation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clubsWithRisk as $club)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $club->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $club->department }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($club->status === 'suspended')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $riskColors = [
                                        'none' => 'bg-green-100 text-green-800',
                                        'low' => 'bg-blue-100 text-blue-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'critical' => 'bg-red-100 text-red-800'
                                    ];
                                    $clubRiskColor = $riskColors[$club->risk_level] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium {{ $clubRiskColor }}">
                                    {{ ucfirst($club->risk_level ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($club->offense_count === 0)
                                    <span class="text-sm text-gray-500">None</span>
                                @elseif($club->offense_count === 1)
                                    <span class="text-sm font-medium text-gray-900">1st Offense</span>
                                @elseif($club->offense_count === 2)
                                    <span class="text-sm font-medium text-orange-700">2nd Offense</span>
                                @else
                                    <span class="text-sm font-medium text-red-700">3rd+ Offense ({{ $club->offense_count }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate" title="{{ $club->recommendation }}">
                                    {{ $club->recommendation }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('head-office.decision-support.club-details', $club->id) }}" 
                                   class="text-gray-900 hover:text-gray-700 font-medium underline">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No clubs found with violation data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Decision Support Legend -->
    <div class="bg-white border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Offense System Guidelines</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                    <h4 class="text-sm font-semibold text-gray-900">First Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-1"><span class="font-medium">Action:</span> Official Warning</p>
                <p class="text-xs text-gray-500">Club receives formal warning. Behavior is documented and monitored.</p>
            </div>
            <div class="border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                    <h4 class="text-sm font-semibold text-gray-900">Second Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-1"><span class="font-medium">Action:</span> Automatic Suspension</p>
                <p class="text-xs text-gray-500">Club is automatically suspended upon confirmation. Must appeal to be reactivated.</p>
            </div>
            <div class="border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                    <h4 class="text-sm font-semibold text-gray-900">Third Offense</h4>
                </div>
                <p class="text-sm text-gray-700 mb-1"><span class="font-medium">Action:</span> Club Termination</p>
                <p class="text-xs text-gray-500">Club registration is permanently revoked. All activities must cease immediately.</p>
            </div>
        </div>
        <div class="mt-4 border border-gray-200 p-3">
            <p class="text-xs text-gray-500">
                <span class="font-medium text-gray-700">Note:</span> Suspensions are enforced automatically when a club reaches 2 confirmed violations. Suspension is lifted automatically when all violations are resolved through the appeals process.
            </p>
        </div>
    </div>

    <!-- Record Violation Modal -->
    <div id="recordViolationModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border border-gray-200 w-[560px] bg-white mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Record Violation</h3>
                    <p class="text-sm text-gray-500 mt-1">Submit an official violation report against a club</p>
                </div>
                <button onclick="closeRecordViolationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="recordViolationForm" class="space-y-5">
                @csrf
                
                <!-- Select Club -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Organization <span class="text-red-500">*</span>
                    </label>
                    <select id="violationClubId" name="club_id" required
                            class="w-full px-3 py-2 border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-colors text-sm bg-white">
                        <option value="">Select an organization</option>
                        @foreach($allClubs as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} &mdash; {{ $c->department }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Violation Type Dropdown -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Violation Type <span class="text-red-500">*</span>
                    </label>
                    <select id="violationType" name="violation_type" required onchange="populateViolationDetails()"
                            class="w-full px-3 py-2 border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-colors text-sm bg-white">
                        <option value="">Select a violation type</option>
                        <optgroup label="Academic Violations">
                            <option value="Unauthorized academic events" data-severity="moderate" data-title="Unauthorized Academic Event" data-desc="Club conducted an academic event without proper authorization or approval from the administration.">Unauthorized academic events</option>
                            <option value="Misrepresentation of academic activities" data-severity="major" data-title="Misrepresentation of Academic Activities" data-desc="Club misrepresented the nature or scope of academic activities in official reports or communications.">Misrepresentation of academic activities</option>
                            <option value="Academic integrity violation" data-severity="major" data-title="Academic Integrity Violation" data-desc="Club members or activities involved in academic dishonesty or integrity violations.">Academic integrity violation</option>
                        </optgroup>
                        <optgroup label="Administrative Violations">
                            <option value="Failure to submit required documents" data-severity="minor" data-title="Failure to Submit Required Documents" data-desc="Club failed to submit required documents, reports, or forms within the specified deadline.">Failure to submit required documents</option>
                            <option value="Unauthorized use of university name or logo" data-severity="moderate" data-title="Unauthorized Use of University Name/Logo" data-desc="Club used the university name, logo, or branding without proper authorization.">Unauthorized use of university name or logo</option>
                            <option value="Non-compliance with administrative directives" data-severity="moderate" data-title="Non-Compliance with Administrative Directives" data-desc="Club failed to comply with official directives or memoranda issued by the administration.">Non-compliance with administrative directives</option>
                            <option value="Failure to maintain minimum membership" data-severity="minor" data-title="Failure to Maintain Minimum Membership" data-desc="Club membership fell below the required minimum number of active members.">Failure to maintain minimum membership</option>
                            <option value="Operating without valid registration" data-severity="major" data-title="Operating Without Valid Registration" data-desc="Club conducted activities or operations without a valid and current registration with the university.">Operating without valid registration</option>
                        </optgroup>
                        <optgroup label="Financial Violations">
                            <option value="Unauthorized fund collection" data-severity="moderate" data-title="Unauthorized Fund Collection" data-desc="Club collected fees or funds from members or the public without proper authorization.">Unauthorized fund collection</option>
                            <option value="Misuse of club funds" data-severity="major" data-title="Misuse of Club Funds" data-desc="Club funds were used for purposes other than their approved and intended use.">Misuse of club funds</option>
                            <option value="Failure to submit financial reports" data-severity="minor" data-title="Failure to Submit Financial Reports" data-desc="Club failed to submit the required financial reports or audits within the deadline.">Failure to submit financial reports</option>
                        </optgroup>
                        <optgroup label="Behavioral Violations">
                            <option value="Hazing or bullying incidents" data-severity="major" data-title="Hazing or Bullying Incident" data-desc="Club members engaged in hazing, bullying, or intimidation activities directed at members or non-members.">Hazing or bullying incidents</option>
                            <option value="Disorderly conduct during events" data-severity="moderate" data-title="Disorderly Conduct During Events" data-desc="Club members exhibited disorderly or disruptive behavior during official events or activities.">Disorderly conduct during events</option>
                            <option value="Violation of campus safety policies" data-severity="moderate" data-title="Violation of Campus Safety Policies" data-desc="Club activities violated campus safety regulations or protocols.">Violation of campus safety policies</option>
                            <option value="Discrimination or harassment" data-severity="major" data-title="Discrimination or Harassment" data-desc="Club or its members engaged in discriminatory practices or harassment based on protected characteristics.">Discrimination or harassment</option>
                            <option value="Property damage or vandalism" data-severity="major" data-title="Property Damage or Vandalism" data-desc="Club activities resulted in damage to university or private property.">Property damage or vandalism</option>
                        </optgroup>
                        <optgroup label="Event Violations">
                            <option value="Unauthorized event or gathering" data-severity="moderate" data-title="Unauthorized Event or Gathering" data-desc="Club organized an event or gathering without obtaining proper permits or approval.">Unauthorized event or gathering</option>
                            <option value="Exceeding approved event scope" data-severity="minor" data-title="Exceeding Approved Event Scope" data-desc="Club event exceeded the approved scope, budget, or participant count as originally authorized.">Exceeding approved event scope</option>
                            <option value="Failure to follow event safety protocols" data-severity="moderate" data-title="Failure to Follow Event Safety Protocols" data-desc="Club failed to implement required safety measures during an organized event.">Failure to follow event safety protocols</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Severity -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Severity <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="severity" value="minor" class="peer hidden" id="sev_minor">
                            <div class="border border-gray-200 p-3 text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-colors">
                                <p class="text-sm font-medium text-gray-900">Minor</p>
                                <p class="text-xs text-gray-500 mt-1">Low impact</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="severity" value="moderate" class="peer hidden" id="sev_moderate">
                            <div class="border border-gray-200 p-3 text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-colors">
                                <p class="text-sm font-medium text-gray-900">Moderate</p>
                                <p class="text-xs text-gray-500 mt-1">Medium impact</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="severity" value="major" class="peer hidden" id="sev_major">
                            <div class="border border-gray-200 p-3 text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-colors">
                                <p class="text-sm font-medium text-gray-900">Major</p>
                                <p class="text-xs text-gray-500 mt-1">High impact</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Violation Title -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Violation Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="violationTitle" name="title" required
                           class="w-full px-3 py-2 border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-colors text-sm"
                           placeholder="Enter violation title">
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="violationDescription" name="description" rows="4" required maxlength="2000"
                              class="w-full px-3 py-2 border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-colors text-sm resize-none"
                              placeholder="Provide details about the violation"></textarea>
                    <p class="text-xs text-gray-500">Max 2000 characters</p>
                </div>

                <!-- Auto-suspension Notice -->
                <div class="border border-gray-200 p-3">
                    <p class="text-xs text-gray-500">
                        <span class="font-medium text-gray-700">Auto-suspension:</span> If recording this violation brings the club to 2 or more confirmed offenses, the club will be automatically suspended.
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeRecordViolationModal()"
                            class="px-5 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="submitViolationBtn"
                            class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">
                        Submit Violation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="hidden fixed inset-0 bg-gray-900/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[70]">
        <div class="relative top-1/3 mx-auto p-6 border border-gray-200 w-[420px] bg-white">
            <div class="flex items-start gap-4">
                <div id="notifIconContainer" class="flex-shrink-0 w-10 h-10 flex items-center justify-center"></div>
                <div class="flex-1">
                    <h3 id="notifTitle" class="text-base font-semibold text-gray-900"></h3>
                    <p id="notifMessage" class="text-sm text-gray-600 mt-2"></p>
                </div>
            </div>
            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
                <button onclick="closeNotificationModal()" class="px-5 py-2 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">OK</button>
            </div>
        </div>
    </div>

    <script>
        // --- Notification Modal ---
        function showNotification(type, title, message, callback) {
            const iconContainer = document.getElementById('notifIconContainer');
            if (type === 'error') {
                iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-red-100 flex items-center justify-center';
                iconContainer.innerHTML = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            } else if (type === 'warning') {
                iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-amber-100 flex items-center justify-center';
                iconContainer.innerHTML = '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            } else {
                iconContainer.className = 'flex-shrink-0 w-10 h-10 bg-green-100 flex items-center justify-center';
                iconContainer.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifMessage').textContent = message;
            document.getElementById('notificationModal').classList.remove('hidden');
            window._notifCallback = callback || null;
        }

        function closeNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
            if (window._notifCallback) {
                window._notifCallback();
                window._notifCallback = null;
            }
        }
        function openRecordViolationModal() {
            document.getElementById('recordViolationModal').classList.remove('hidden');
        }

        function closeRecordViolationModal() {
            document.getElementById('recordViolationModal').classList.add('hidden');
            document.getElementById('recordViolationForm').reset();
        }

        function populateViolationDetails() {
            const select = document.getElementById('violationType');
            const selected = select.options[select.selectedIndex];
            
            if (selected.dataset.severity) {
                const severityRadio = document.getElementById('sev_' + selected.dataset.severity);
                if (severityRadio) severityRadio.checked = true;
            }
            if (selected.dataset.title) {
                document.getElementById('violationTitle').value = selected.dataset.title;
            }
            if (selected.dataset.desc) {
                document.getElementById('violationDescription').value = selected.dataset.desc;
            }
        }

        // Handle form submission
        document.getElementById('recordViolationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitViolationBtn');
            
            // Validate severity is selected
            if (!formData.get('severity')) {
                showNotification('warning', 'Missing Field', 'Please select a severity level.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            fetch('{{ route("head-office.decision-support.record-violation") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRecordViolationModal();
                    showNotification('success', 'Violation Recorded', data.message, () => {
                        window.location.reload();
                    });
                } else {
                    showNotification('error', 'Error', data.error || data.message || 'Error recording violation.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', 'An error occurred while recording the violation.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Violation';
            });
        });
    </script>
</x-dashboard-layout>
