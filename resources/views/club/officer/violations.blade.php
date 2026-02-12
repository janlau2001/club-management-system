<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View & Appeal Violations - {{ $club->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a]">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Club Violations & Appeals</h1>
                    <p class="text-white opacity-90">{{ $club->name }} • {{ $club->department }}</p>
                </div>
                <a href="{{ route('club.officer.dashboard') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Main Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-3" x-data="{ activeTab: 'all' }">
                        <!-- Violations List -->
                        <div class="bg-white border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900">Violation History</h2>
                                <p class="text-sm text-gray-600 mt-1">Review violations issued by Head Office and submit appeals</p>
                            </div>

                            <!-- Tab Navigation -->
                            <div class="border-b border-gray-200">
                                <nav class="flex">
                                    <button @click="activeTab = 'all'" 
                                        :class="activeTab === 'all' ? 'border-b-2 border-gray-900 text-gray-900 bg-gray-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                        class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors">
                                        All
                                        <span class="ml-1.5 px-2 py-0.5 text-xs bg-gray-200 text-gray-700">{{ $violations->count() }}</span>
                                    </button>
                                    <button @click="activeTab = 'confirmed'" 
                                        :class="activeTab === 'confirmed' ? 'border-b-2 border-red-600 text-red-700 bg-red-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                        class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors">
                                        Confirmed
                                        <span class="ml-1.5 px-2 py-0.5 text-xs bg-red-100 text-red-700">{{ $confirmedCount }}</span>
                                    </button>
                                    <button @click="activeTab = 'appealed'" 
                                        :class="activeTab === 'appealed' ? 'border-b-2 border-yellow-600 text-yellow-700 bg-yellow-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                        class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors">
                                        Appealed
                                        <span class="ml-1.5 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700">{{ $appealedCount }}</span>
                                    </button>
                                    <button @click="activeTab = 'dismissed'" 
                                        :class="activeTab === 'dismissed' ? 'border-b-2 border-green-600 text-green-700 bg-green-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                        class="flex-1 px-4 py-3 text-sm font-medium text-center transition-colors">
                                        Dismissed
                                        <span class="ml-1.5 px-2 py-0.5 text-xs bg-green-100 text-green-700">{{ $resolvedCount }}</span>
                                    </button>
                                </nav>
                            </div>

                            <div class="divide-y divide-gray-200">
                        @forelse($violations as $violation)
                            @php
                                $latestAppeal = $violation->appeals->first();
                                $canAppeal = $violation->status === 'confirmed' && (!$latestAppeal || $latestAppeal->status === 'rejected');
                                
                                // Status colors
                                $statusColors = [
                                    'confirmed' => 'bg-red-100 text-red-800 border-red-200',
                                    'appealed' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'dismissed' => 'bg-green-100 text-green-800 border-green-200',
                                    'pending' => 'bg-blue-100 text-blue-800 border-blue-200',
                                ];
                                $statusColor = $statusColors[$violation->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                
                                // Severity colors
                                $severityColors = [
                                    'minor' => 'bg-yellow-100 text-yellow-800',
                                    'moderate' => 'bg-orange-100 text-orange-800',
                                    'major' => 'bg-red-100 text-red-800',
                                ];
                                $severityColor = $severityColors[$violation->severity] ?? 'bg-gray-100 text-gray-800';
                            @endphp

                            <div x-show="activeTab === 'all' || activeTab === '{{ $violation->status }}'" x-cloak class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $violation->title }}</h3>
                                            <span class="px-2.5 py-1 text-xs font-medium border {{ $statusColor }}">
                                                {{ ucfirst($violation->status) }}
                                            </span>
                                            <span class="px-2.5 py-1 text-xs font-medium {{ $severityColor }}">
                                                {{ ucfirst($violation->severity) }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-700 mb-3">{{ $violation->description }}</p>
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $violation->violation_date->format('M d, Y') }}
                                            </div>
                                        </div>

                                        <!-- Appeal Information -->
                                        @if($latestAppeal)
                                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-blue-900">
                                                            Appeal Status: 
                                                            <span class="font-semibold">{{ ucfirst($latestAppeal->status) }}</span>
                                                        </p>
                                                        <p class="text-xs text-blue-700 mt-1">
                                                            Submitted: {{ $latestAppeal->submitted_at->format('M d, Y g:i A') }}
                                                        </p>
                                                        @if($latestAppeal->status === 'rejected' && $latestAppeal->review_notes)
                                                            <p class="text-xs text-blue-700 mt-1">
                                                                Reason: {{ $latestAppeal->review_notes }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Appeal Button -->
                                    <div class="ml-6">
                                        @if($canAppeal)
                                            <a
                                                href="{{ route('club.officer.appeal-form', $violation->id) }}"
                                                class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            >
                                                📝 Submit Appeal
                                            </a>
                                        @elseif($latestAppeal && $latestAppeal->status === 'pending')
                                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium border border-yellow-200">
                                                ⏳ Pending Review
                                            </span>
                                        @elseif($latestAppeal && $latestAppeal->status === 'approved')
                                            <span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-medium border border-green-200">
                                                ✅ Appeal Approved
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium">
                                                Cannot Appeal
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Violations Found</h3>
                                <p class="text-gray-500">Your club has no violation records at this time.</p>
                            </div>
                        @endforelse

                        @if($violations->count() > 0)
                            <!-- Per-tab empty states -->
                            <template x-if="activeTab === 'confirmed' && {{ $confirmedCount }} === 0">
                                <div class="p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Confirmed Violations</h3>
                                    <p class="text-gray-500">There are no confirmed violations at this time.</p>
                                </div>
                            </template>
                            <template x-if="activeTab === 'appealed' && {{ $appealedCount }} === 0">
                                <div class="p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-yellow-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Appealed Violations</h3>
                                    <p class="text-gray-500">There are no violations currently under appeal.</p>
                                </div>
                            </template>
                            <template x-if="activeTab === 'dismissed' && {{ $resolvedCount }} === 0">
                                <div class="p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Dismissed Violations</h3>
                                    <p class="text-gray-500">There are no dismissed violations at this time.</p>
                                </div>
                            </template>
                        @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar - Status Note -->
                    <div class="lg:col-span-1">
                        @if($club->status === 'suspended' && $confirmedCount > 0)
                            <div class="bg-orange-50 border border-orange-200 p-4 sticky top-6">
                                <div class="flex items-start mb-3">
                                    <svg class="w-5 h-5 text-orange-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <h3 class="ml-2 text-sm font-semibold text-orange-900">Club Suspended</h3>
                                </div>
                                <p class="text-xs text-orange-800 mb-3">
                                    Your club is currently <strong>suspended</strong>. Appeal all confirmed violations to lift the suspension.
                                </p>
                                <div class="bg-white rounded p-3 border border-orange-200 mb-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs text-orange-700">Confirmed</span>
                                        <span class="text-lg font-bold text-orange-900">{{ $confirmedCount }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-orange-700">To Appeal</span>
                                        <span class="text-lg font-bold text-orange-900">{{ $confirmedCount }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-orange-700">
                                    <strong>Note:</strong> Each violation must be appealed individually. All appeals must be approved to lift suspension.
                                </p>
                            </div>
                        @elseif($club->status === 'suspended' && $appealedCount > 0)
                            <div class="bg-blue-50 border border-blue-200 p-4 sticky top-6">
                                <div class="flex items-start mb-3">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="ml-2 text-sm font-semibold text-blue-900">Appeals Pending</h3>
                                </div>
                                <p class="text-xs text-blue-800 mb-3">
                                    Your appeals are being reviewed by the Head Office. You'll be notified of the decision.
                                </p>
                                <div class="bg-white rounded p-3 border border-blue-200">
                                    <p class="text-xs text-blue-900">
                                        Club will remain suspended until all appeals are approved.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
