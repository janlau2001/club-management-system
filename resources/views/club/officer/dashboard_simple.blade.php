<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $club->name }} - {{ $clubUser->role === 'adviser' ? 'Adviser' : 'Officer' }} Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a] shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $club->name }}</h1>
                    <p class="text-white opacity-90">{{ $club->department }} • {{ $club->club_type }}</p>
                    <p class="text-white opacity-75 text-sm">Welcome, {{ $clubUser->name }} ({{ $clubUser->getDisplayRole() }})</p>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('club.logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto p-6 space-y-6">
            
            @if($club->status === 'suspended')
                <!-- Suspension Alert -->
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">Club Suspended</h3>
                            <p class="text-red-600">Your club has been suspended due to violations. Only officers and advisers can access this dashboard.</p>
                            <p class="text-red-600 text-sm mt-2">Regular members and other officers cannot access the system until the club is reactivated.</p>
                            
                            <!-- Simple Appeal Section -->
                            <div class="mt-4">
                                <button 
                                    onclick="showViolationsList()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                >
                                    📋 View & Appeal Violations
                                </button>
                            </div>
                            
                            <!-- Simple Violations List -->
                            <div id="violationsList" class="hidden mt-6 bg-white rounded-lg border p-4">
                                <h4 class="font-semibold text-gray-900 mb-4">Club Violations</h4>
                                <div id="violationsContent">
                                    <p class="text-gray-500">Loading violations...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Members</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $club->member_count ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Club Status</p>
                            <p class="text-2xl font-bold {{ $club->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($club->status) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Registered Since</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $club->date_registered ? $club->date_registered->format('Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="#" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium text-gray-900">Manage Members</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple violations and appeals functions
        function showViolationsList() {
            const listDiv = document.getElementById('violationsList');
            listDiv.classList.remove('hidden');
            loadViolations();
        }

        function loadViolations() {
            const contentDiv = document.getElementById('violationsContent');
            contentDiv.innerHTML = '<p class="text-gray-500">Loading violations...</p>';

            fetch('/club/officer/violations')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.violations.length > 0) {
                        let html = '<div class="space-y-3">';
                        data.violations.forEach(violation => {
                            html += `
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900">${violation.title}</h5>
                                            <p class="text-sm text-gray-600 mt-1">${violation.description}</p>
                                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                                <span>Date: ${violation.violation_date}</span>
                                                <span>Points: ${violation.points}</span>
                                                <span class="px-2 py-1 rounded ${violation.severity_color}">${violation.severity}</span>
                                                <span class="px-2 py-1 rounded ${violation.status_color}">${violation.status}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            ${violation.can_appeal ? 
                                                `<button onclick="appealViolation(${violation.id}, '${violation.title.replace(/'/g, '\\\'')}')" 
                                                         class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                    Appeal
                                                 </button>` : 
                                                violation.appeal_status ? 
                                                `<span class="px-2 py-1 text-xs rounded ${violation.appeal_status_color}">Appeal ${violation.appeal_status}</span>` :
                                                `<span class="text-xs text-gray-500">Cannot appeal</span>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        contentDiv.innerHTML = html;
                    } else {
                        contentDiv.innerHTML = '<p class="text-gray-500">No violations found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading violations:', error);
                    contentDiv.innerHTML = '<p class="text-red-500">Error loading violations.</p>';
                });
        }

        function appealViolation(violationId, violationTitle) {
            const reason = prompt(`Appeal for: ${violationTitle}\n\nPlease provide your reason:`, 'We believe this violation was issued in error.');
            
            if (!reason || reason.trim() === '') {
                return;
            }

            fetch('/club/officer/submit-appeal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    violation_id: violationId,
                    reason: reason.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotificationModal('Success!', 'Appeal submitted successfully!', 'success');
                    loadViolations(); // Refresh the list
                } else {
                    showNotificationModal('Error', data.error || 'Unknown error', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotificationModal('Error', 'Error submitting appeal.', 'error');
            });
        }
    </script>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div id="notificationIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full">
                    <!-- Icon will be injected here -->
                </div>
                <div class="mt-2 px-7 py-3">
                    <h3 id="notificationTitle" class="text-lg font-medium text-gray-900 text-center"></h3>
                    <p id="notificationMessage" class="text-sm text-gray-600 mt-2 text-center"></p>
                    <div class="flex items-center justify-center mt-4">
                        <button type="button" onclick="hideNotificationModal()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showNotificationModal(title, message, type = 'success') {
            const modal = document.getElementById('notificationModal');
            const icon = document.getElementById('notificationIcon');
            const titleEl = document.getElementById('notificationTitle');
            const messageEl = document.getElementById('notificationMessage');

            titleEl.textContent = title;
            messageEl.textContent = message;

            // Set icon based on type
            if (type === 'success') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else if (type === 'warning') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100';
                icon.innerHTML = '<svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            }

            modal.classList.remove('hidden');
        }

        function hideNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
        }
    </script>
</body>
</html>
