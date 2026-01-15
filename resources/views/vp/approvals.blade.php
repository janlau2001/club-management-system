<x-dashboard-layout>
    <x-slot name="title">VP Approvals</x-slot>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">VP Approvals</h1>
        <p class="text-gray-600 mt-2">Final review and approval of club registration applications</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <!-- Pending Tab -->
                <a href="{{ route('vp.approvals', ['status' => 'pending']) }}" 
                   class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                          {{ $status === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $status === 'pending' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending
                    <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium
                                 {{ $status === 'pending' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $pendingCount }}
                    </span>
                </a>

                <!-- Approved Tab -->
                <a href="{{ route('vp.approvals', ['status' => 'approved']) }}" 
                   class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors
                          {{ $status === 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $status === 'approved' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approved
                    <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium
                                 {{ $status === 'approved' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $approvedCount }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- Tab Content Description -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    @if($status === 'pending')
                        <span class="font-medium text-gray-900">{{ $registrations->count() }}</span> pending registration(s) - You can only approve applications after Director has noted
                    @else
                        <span class="font-medium text-gray-900">{{ $registrations->count() }}</span> registration(s) approved by you
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">VP Final Approvals</h2>
            <p class="text-gray-600 mt-1">Applications ready for final VP approval (Director-noted applications)</p>
        </div>

        @if($registrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->club_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->nature }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->officer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->officer->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $registration->department }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $registration->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->approved_by_vp)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved & Registered
                                        </span>
                                    @elseif($registration->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @elseif($registration->noted_by_director && $registration->approved_by_psg_council && $registration->endorsed_by_dean)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Ready for VP Approval
                                        </span>
                                    @elseif(!$registration->endorsed_by_dean)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            ⏳ Awaiting Dean
                                        </span>
                                    @elseif(!$registration->approved_by_psg_council)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            ⏳ Awaiting PSG Council
                                        </span>
                                    @elseif(!$registration->noted_by_director)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            ⏳ Awaiting Director
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('vp.approvals.show', $registration) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        View Details
                                    </a>
                                    @if(!$registration->approved_by_vp && $registration->status !== 'rejected')
                                        @if($registration->endorsed_by_dean && $registration->approved_by_psg_council && $registration->noted_by_director)
                                            <button type="button"
                                                    class="text-green-600 hover:text-green-900 mr-3"
                                                    onclick="openApproveModal('{{ $registration->club_name }}', '{{ route('vp.approvals.approve', $registration) }}')">
                                                Approve
                                            </button>
                                            <button type="button"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="openRejectModal('{{ $registration->club_name }}', '{{ route('vp.approvals.reject', $registration) }}')">
                                                Reject
                                            </button>
                                        @elseif(!$registration->endorsed_by_dean)
                                            <span class="text-gray-400 text-sm italic">⏳ Awaiting Dean endorsement</span>
                                        @elseif(!$registration->approved_by_psg_council)
                                            <span class="text-gray-400 text-sm italic">⏳ Awaiting PSG Council approval</span>
                                        @elseif(!$registration->noted_by_director)
                                            <span class="text-gray-400 text-sm italic">⏳ Awaiting Director noting</span>
                                        @endif
                                    @elseif($registration->approved_by_vp)
                                        <span class="text-green-600 text-sm">✓ Club Registered</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No pending applications</h3>
                <p class="mt-1 text-sm text-gray-500">All applications have been processed or are in earlier approval stages.</p>
            </div>
        @endif
    </div>

    <!-- Approve Registration Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Final Approval & Registration</h3>
                <div class="mt-4 mb-6">
                    <p class="text-sm text-gray-500 mb-4" id="approveMessage">Are you sure you want to approve this registration?</p>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                        <p class="text-xs text-green-700 font-medium">✓ This will officially register the club and make it active</p>
                        <p class="text-xs text-green-600 mt-1">• Club will appear on organization dashboards</p>
                        <p class="text-xs text-green-600">• All admins will be able to see the registered club</p>
                    </div>
                    <p class="text-xs text-red-700 mb-2">This is a critical action. Please enter your password to confirm.</p>
                    <div class="flex items-center text-xs text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Verifying as: <strong>Vice President, Student Affairs & Services</strong></span>
                    </div>
                </div>

                <div class="text-left">
                    <form id="approveForm" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                            <input type="password" name="password" required placeholder="Enter your password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeApproveModal()" 
                                    class="flex-1 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-md hover:bg-green-800">
                                Approve & Register Club
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Reject Registration</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="rejectMessage">Are you sure you want to reject this registration?</p>
                </div>
                <div class="text-left">
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                      placeholder="Please provide a reason for rejecting this application..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                            <input type="password" name="password" required placeholder="Enter your password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeRejectModal()" 
                                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Reject Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(clubName, url) {
            document.getElementById('approveMessage').textContent = 'Are you sure you want to approve and register "' + clubName + '"?';
            document.getElementById('approveForm').action = url;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(clubName, url) {
            document.getElementById('rejectMessage').textContent = 'Are you sure you want to reject the registration for "' + clubName + '"?';
            document.getElementById('rejectForm').action = url;
            document.getElementById('rejectModal').classList.remove('hidden');
            
            // Clear the textarea
            document.getElementById('rejection_reason').value = '';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Handle form submissions with AJAX
        document.getElementById('approveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
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
                    closeApproveModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
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
                    closeRejectModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            
            if (event.target === approveModal) {
                closeApproveModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        }
    </script>
</x-dashboard-layout>
