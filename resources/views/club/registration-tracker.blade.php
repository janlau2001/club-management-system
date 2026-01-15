<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration Tracker - Club Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen font-sans flex items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-4xl backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-200 transition-all duration-700 bg-gradient-to-br from-[#29553c] to-[#031a0a]">
        <div class="flex min-h-[600px]">
            <!-- Left Side - Tracker Info -->
            <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
                <!-- Background Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <!-- Tracker Welcome Text -->
                <div class="max-w-sm text-center text-white relative z-10">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-3">TRACK YOUR</h1>
                    <h2 class="text-2xl font-bold mb-4">REGISTRATION</h2>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Monitor the progress of your club registration application through our approval process.
                    </p>
                </div>
            </div>

            <!-- Right Side - Tracker Form -->
            <div class="flex-1 bg-black/20 backdrop-blur-sm flex items-center justify-center p-8 relative">
                <div class="w-full max-w-sm">
                    @if(!isset($registration))
                        <!-- Login Form -->
                        <form method="POST" action="{{ route('club.registration-tracker.check') }}" class="space-y-5">
                            @csrf

                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-white mb-1">REGISTRATION TRACKER</h2>
                                <p class="text-green-200 text-sm">Enter your officer credentials</p>
                            </div>

                            <!-- Success/Error Messages -->
                            @if(session('success'))
                                <div class="bg-green-500/20 border border-green-500/50 text-white px-4 py-3 rounded-lg text-sm mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded-lg text-sm">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Email -->
                            <div class="relative">
                                <input id="email" name="email" type="email" required
                                       value="{{ old('email') }}"
                                       placeholder="Officer Email"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                            </div>

                            <!-- Password -->
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                       placeholder="Password"
                                       class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-sm">
                                Check Registration Status
                            </button>

                            <!-- Back to Login Link -->
                            <div class="text-center">
                                <a href="{{ route('club.login') }}" class="text-white/80 hover:text-white text-xs transition-colors">
                                    ← Back to Club Login
                                </a>
                            </div>
                        </form>
                    @else
                        <!-- Registration Status Display -->
                        <div class="space-y-6">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-white mb-1">REGISTRATION STATUS</h2>
                                <p class="text-green-200 text-sm">{{ $registration->club_name }}</p>
                            </div>

                            <!-- Officer Info -->
                            <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                                <h3 class="text-white font-semibold text-sm mb-2">Officer Information</h3>
                                <div class="text-white/80 text-xs space-y-1">
                                    <p><span class="font-medium">Name:</span> {{ $officer->name }}</p>
                                    <p><span class="font-medium">Position:</span> {{ $officer->position }}</p>
                                    <p><span class="font-medium">Department:</span> {{ $officer->department }}</p>
                                </div>
                            </div>

                            <!-- Progress Steps -->
                            <div class="space-y-3">
                                <!-- Step 1: Submitted -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="text-white text-sm">
                                        <div class="font-medium">Application Submitted</div>
                                        <div class="text-white/60 text-xs">{{ $registration->submitted_at->format('M j, Y g:i A') }}</div>
                                    </div>
                                </div>

                                <!-- Step 2: Dean Endorsement -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 {{ $registration->endorsed_by_dean ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full flex items-center justify-center flex-shrink-0">
                                        @if($registration->endorsed_by_dean)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-white text-sm">
                                        <div class="font-medium">Dean Endorsement</div>
                                        <div class="text-white/60 text-xs">
                                            @if($registration->endorsed_by_dean)
                                                Endorsed on {{ $registration->endorsed_by_dean_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending dean endorsement
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: PSG Council Approval -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 {{ $registration->approved_by_psg_council ? 'bg-green-500' : ($registration->endorsed_by_dean ? 'bg-yellow-500' : 'bg-gray-500') }} rounded-full flex items-center justify-center flex-shrink-0">
                                        @if($registration->approved_by_psg_council)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($registration->endorsed_by_dean)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="text-white text-sm">
                                        <div class="font-medium">PSG Council Approval</div>
                                        <div class="text-white/60 text-xs">
                                            @if($registration->approved_by_psg_council)
                                                Approved on {{ $registration->approved_by_psg_council_at->format('M j, Y g:i A') }}
                                            @elseif($registration->endorsed_by_dean)
                                                Pending PSG Council approval
                                            @else
                                                Waiting for dean endorsement
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Director Noting -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 {{ $registration->noted_by_director ? 'bg-green-500' : ($registration->approved_by_psg_council ? 'bg-yellow-500' : 'bg-gray-500') }} rounded-full flex items-center justify-center flex-shrink-0">
                                        @if($registration->noted_by_director)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($registration->approved_by_psg_council)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="text-white text-sm">
                                        <div class="font-medium">Director Approval</div>
                                        <div class="text-white/60 text-xs">
                                            @if($registration->noted_by_director)
                                                Approved on {{ $registration->noted_by_director_at->format('M j, Y g:i A') }}
                                            @elseif($registration->approved_by_psg_council)
                                                Pending director approval
                                            @else
                                                Waiting for PSG Council approval
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: VP Approval -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 {{ $registration->approved_by_vp ? 'bg-green-500' : ($registration->noted_by_director ? 'bg-yellow-500' : 'bg-gray-500') }} rounded-full flex items-center justify-center flex-shrink-0">
                                        @if($registration->approved_by_vp)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($registration->noted_by_director)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="text-white text-sm">
                                        <div class="font-medium">VP Approval</div>
                                        <div class="text-white/60 text-xs">
                                            @if($registration->approved_by_vp)
                                                Approved on {{ $registration->approved_by_vp_at->format('M j, Y g:i A') }}
                                            @elseif($registration->noted_by_director)
                                                Pending VP approval
                                            @else
                                                Waiting for director approval
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="text-center">
                                @if($registration->status === 'approved')
                                    <div class="bg-green-500/20 border border-green-500/50 text-green-200 px-4 py-3 rounded-lg text-sm">
                                        <div class="font-semibold">🎉 Registration Approved!</div>
                                        <div class="text-xs mt-1">Your club is now active and you can login to manage it.</div>
                                    </div>
                                @elseif($registration->status === 'rejected')
                                    <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg text-sm">
                                        <div class="font-semibold">❌ Registration Rejected</div>
                                        @if($registration->rejection_reason)
                                            <div class="text-xs mt-2 text-left">
                                                <div class="font-medium">Reason:</div>
                                                <div class="text-red-100 bg-red-600/20 p-2 rounded mt-1">{{ $registration->rejection_reason }}</div>
                                            </div>
                                        @endif
                                        @if($registration->rejected_by)
                                            <div class="text-xs mt-2 text-left">
                                                <div class="font-medium">Rejected by:</div>
                                                <div class="text-red-100">{{ $registration->rejected_by }}</div>
                                            </div>
                                        @endif
                                        @if($registration->rejected_at)
                                            <div class="text-xs mt-1 text-left">
                                                <div class="font-medium">Date:</div>
                                                <div class="text-red-100">{{ $registration->rejected_at->format('M j, Y g:i A') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-200 px-4 py-3 rounded-lg text-sm">
                                        <div class="font-semibold">⏳ Under Review</div>
                                        <div class="text-xs mt-1">Your application is being processed by the administration.</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <a href="{{ route('club.registration-tracker') }}" 
                                   class="flex-1 bg-white/10 hover:bg-white/20 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-sm text-center">
                                    Return to Tracker
                                </a>
                                @if($registration->status === 'approved')
                                    <a href="{{ route('club.login') }}" 
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm text-center">
                                        Login to Club
                                    </a>
                                @elseif($registration->status === 'rejected')
                                    <a href="{{ route('club.registration-reedit', $registration) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 text-sm text-center">
                                        Re-edit & Resubmit
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
