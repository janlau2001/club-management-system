<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View Application - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a]">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Application Details</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                </div>
                <a href="{{ route('club.officer.applicants') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white transition-colors">
                    ← Back to Applications
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-6">
            <div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('club.officer.applicants') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Applications
        </a>
    </div>

    <!-- Application Status -->
    <div class="bg-white border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $application->first_name }} {{ $application->last_name }}
                    @if($application->suffix)
                        {{ $application->suffix }}
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">Applied on {{ $application->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                @if($application->status === 'pending')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold bg-yellow-100 text-yellow-800">
                        Pending Review
                    </span>
                @elseif($application->status === 'approved')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold bg-green-100 text-green-800">
                        Approved
                    </span>
                @else
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold bg-red-100 text-red-800">
                        Rejected
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Application Details -->
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                    <p class="mt-1 text-base text-gray-900">
                        {{ $application->first_name }} {{ $application->last_name }}
                        @if($application->suffix)
                            {{ $application->suffix }}
                        @endif
                    </p>
                </div>

                @if($application->position !== 'adviser')
                <div>
                    <label class="text-sm font-medium text-gray-500">Age</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->age }} years old</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Gender</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->gender }}</p>
                </div>

                @if($application->position === 'adviser')
                <div>
                    <label class="text-sm font-medium text-gray-500">Professor ID</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->professor_id }}</p>
                </div>
                @else
                <div>
                    <label class="text-sm font-medium text-gray-500">Student ID</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->student_id }}</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Email Address</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->email }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Phone Number</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->phone_number }}</p>
                </div>

                @if($application->position === 'adviser')
                <div>
                    <label class="text-sm font-medium text-gray-500">Department Office</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->department_office }}</p>
                </div>
                @else
                <div>
                    <label class="text-sm font-medium text-gray-500">Course/Program</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->department }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Year Level</label>
                    <p class="mt-1 text-base text-gray-900">{{ $application->year_level }}</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Position Applying For</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold 
                            @if($application->position === 'officer') bg-blue-100 text-blue-800
                            @elseif($application->position === 'adviser') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($application->position) }}
                        </span>
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Application Status</label>
                    <p class="mt-1">
                        @if($application->status === 'pending')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($application->status === 'approved')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold bg-green-100 text-green-800">
                                Approved on {{ $application->approved_at->format('M d, Y') }}
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold bg-red-100 text-red-800">
                                Rejected on {{ $application->rejected_at->format('M d, Y') }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            @if($application->status === 'rejected' && $application->rejection_reason)
                <div class="mt-6 bg-red-50 border border-red-200 p-4">
                    <label class="text-sm font-medium text-red-800">Rejection Reason</label>
                    <p class="mt-1 text-sm text-red-700">{{ $application->rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($application->status === 'pending')
        <div class="mt-6 bg-white border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Application</h3>
            <div class="flex items-center space-x-4">
                <button onclick="approveApplication({{ $application->id }})"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 transition-all duration-200 font-medium">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approve Application
                    </div>
                </button>
                <button onclick="showRejectModal()"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 transition-all duration-200 font-medium">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Application
                    </div>
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto w-96 border border-gray-200 bg-white">
        <div class="bg-gray-900 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Reject Application</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this application:</p>
            <textarea id="rejectionReason" 
                      rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 focus:outline-none focus:border-gray-900"
                      placeholder="Enter rejection reason..."></textarea>
            <div class="flex items-center space-x-3 mt-4">
                <button onclick="hideRejectModal()" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmReject({{ $application->id }})" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 transition-colors">
                    Reject
                </button>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>

<script>
function approveApplication(applicationId) {
    if (!confirm('Are you sure you want to approve this application? The applicant will be added as a member and can log in immediately.')) {
        return;
    }

    fetch(`/club/officer/applicants/${applicationId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("club.officer.applicants") }}';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectionReason').value = '';
}

function confirmReject(applicationId) {
    const reason = document.getElementById('rejectionReason').value.trim();
    
    if (!reason) {
        alert('Please provide a reason for rejection.');
        return;
    }

    fetch(`/club/officer/applicants/${applicationId}/reject`, {
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
            alert(data.message);
            window.location.href = '{{ route("club.officer.applicants") }}';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
</body>
</html>
