<x-dashboard-layout>
    <x-slot name="title">Director Renewal Approvals</x-slot>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Director Renewal Approvals</h1>
        <p class="text-gray-600 mt-2">Review and endorse club renewal applications</p>
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">Pending Renewals</h2>
            <p class="text-gray-600 mt-1">Renewals waiting for Director endorsement</p>
        </div>

        @if($renewals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($renewals as $renewal)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $renewal->club->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $renewal->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($renewal->endorsed_by_osa)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Endorsed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending Endorsement
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('director.renewal-approvals.show', $renewal) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        View Details
                                    </a>
                                    @if(!$renewal->endorsed_by_osa)
                                        <button type="button"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="openConfirmModal('endorse', '{{ $renewal->club->name }}', '{{ route('director.renewal-approvals.endorse', $renewal) }}', 'POST')">
                                            Endorse
                                        </button>
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No pending renewals</h3>
                <p class="mt-1 text-sm text-gray-500">All renewals have been processed.</p>
            </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Confirm Action</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modalMessage">Are you sure you want to perform this action?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="confirmForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enter your password to confirm</label>
                            <input type="password" name="current_password" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeConfirmModal()" 
                                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md hover:bg-gray-600 flex-1">
                                Cancel
                            </button>
                            <button type="submit" id="confirmButton"
                                    class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md hover:bg-green-700 flex-1">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openConfirmModal(action, clubName, url, method) {
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('confirmForm');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const button = document.getElementById('confirmButton');
            
            title.textContent = `${action.charAt(0).toUpperCase() + action.slice(1)} Renewal`;
            message.textContent = `Are you sure you want to ${action} the renewal for "${clubName}"?`;
            button.textContent = action.charAt(0).toUpperCase() + action.slice(1);
            
            form.action = url;
            form.method = method;
            
            modal.classList.remove('hidden');
        }
        
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmForm').reset();
        }
    </script>
</x-dashboard-layout>
