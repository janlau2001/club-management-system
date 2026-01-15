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
