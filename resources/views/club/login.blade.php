<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Club Login - Student Organization Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen font-sans flex items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-5xl backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-gray-200 transition-all duration-700 bg-gradient-to-br from-[#29553c] to-[#031a0a]">
        <div class="flex min-h-[600px]">
            <!-- Left Side - Welcome Content -->
            <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
                <!-- Background Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <!-- Club Welcome Text -->
                <div class="max-w-sm text-center text-white relative z-10">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-3">WELCOME TO</h1>
                    <h2 class="text-2xl font-bold mb-4">CLUB MANAGEMENT</h2>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Join and manage your student organizations. Connect with fellow students and build lasting memories.
                    </p>
                </div>
            </div>

            <!-- Right Side - Club Login Form -->
            <div class="flex-1 bg-black/20 backdrop-blur-sm flex items-center justify-center p-8 relative">
                <div class="w-full max-w-sm">
                    <form method="POST" action="{{ route('club.login') }}" class="space-y-5">
                        @csrf

                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-white mb-1">CLUB LOGIN</h2>
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
                                   placeholder="Your Email"
                                   class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   placeholder="Password"
                                   class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm text-sm">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-sm">
                            Connect to Club
                        </button>

                        <!-- Admin Login Link -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-xs transition-colors">
                                Admin Login →
                            </a>
                        </div>
                    </form>

                    <!-- Club Registration Section -->
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <div class="text-center">
                            <h3 class="text-white font-semibold text-sm mb-2">No club yet?</h3>
                            <p class="text-white/70 text-xs mb-4 leading-relaxed">
                                Start your own student organization and build a community around your interests.
                            </p>
                            <div class="space-y-3">
                                <a href="{{ route('club.register') }}"
                                   class="block bg-white/10 hover:bg-white/20 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30 text-sm">
                                    Register a Club Now!
                                </a>
                                <a href="{{ route('club.registration-tracker') }}"
                                   class="block bg-white/5 hover:bg-white/15 text-white/80 font-medium py-2 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/20 text-xs">
                                    Track Registration Status
                                </a>
                                
                                <div class="flex items-center my-3">
                                    <div class="flex-1 h-px bg-white/20"></div>
                                    <span class="px-3 text-white/50 text-xs">Or</span>
                                    <div class="flex-1 h-px bg-white/20"></div>
                                </div>
                                
                                <button onclick="showJoinClubModal()"
                                   class="block w-full bg-white/10 hover:bg-white/20 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30 text-sm">
                                    Join a Club Now!
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Join Club Modal -->
    <div id="joinClubModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden" style="z-index: 9999;">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-2xl rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Join a Club</h3>
                    <button onclick="hideJoinClubModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 mb-6">
                    Browse and join registered clubs in your university. Select a department to filter clubs.
                </p>

                <!-- Department Filter -->
                <div class="mb-6">
                    <label for="departmentFilter" class="block text-sm font-medium text-gray-700 mb-2">
                        Filter by Department
                    </label>
                    <select id="departmentFilter" onchange="filterClubs()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="all">All Departments</option>
                        <option value="SASTE">SASTE</option>
                        <option value="SBAHM">SBAHM</option>
                        <option value="SNAHS">SNAHS</option>
                        <option value="SITE">SITE</option>
                        <option value="BEU">BEU</option>
                        <option value="SOM">SOM</option>
                        <option value="GRADUATE SCHOOL">GRADUATE SCHOOL</option>
                    </select>
                </div>

                <!-- Loading State -->
                <div id="clubsLoading" class="text-center py-8 hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <p class="text-gray-600 mt-2">Loading clubs...</p>
                </div>

                <!-- Clubs List -->
                <div id="clubsList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Clubs will be loaded here -->
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-8 hidden">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 mt-2">No clubs found for this department</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allClubs = [];

        // Clear localStorage if coming from cancelled registration
        @if(session('clear_local_storage'))
        localStorage.removeItem('officerFormData');
        localStorage.removeItem('clubFormData');
        localStorage.removeItem('registrationInProgress');
        console.log('Registration data cleared from localStorage');
        @endif

        function showJoinClubModal() {
            document.getElementById('joinClubModal').classList.remove('hidden');
            loadClubs();
        }

        function hideJoinClubModal() {
            document.getElementById('joinClubModal').classList.add('hidden');
        }

        async function loadClubs() {
            const loading = document.getElementById('clubsLoading');
            const clubsList = document.getElementById('clubsList');
            const emptyState = document.getElementById('emptyState');

            loading.classList.remove('hidden');
            clubsList.innerHTML = '';
            emptyState.classList.add('hidden');

            try {
                const response = await fetch('/club/registered-clubs');
                const data = await response.json();
                
                allClubs = data.clubs || [];
                filterClubs();
            } catch (error) {
                console.error('Error loading clubs:', error);
                clubsList.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <p>Error loading clubs. Please try again.</p>
                    </div>
                `;
            } finally {
                loading.classList.add('hidden');
            }
        }

        function filterClubs() {
            const filter = document.getElementById('departmentFilter').value;
            const clubsList = document.getElementById('clubsList');
            const emptyState = document.getElementById('emptyState');

            let filteredClubs = allClubs;
            if (filter !== 'all') {
                filteredClubs = allClubs.filter(club => club.department === filter);
            }

            if (filteredClubs.length === 0) {
                clubsList.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            clubsList.innerHTML = filteredClubs.map((club, index) => {
                const maxLength = 150; // Max characters before truncation
                const hasLongDescription = club.description && club.description.length > maxLength;
                const truncatedDesc = hasLongDescription ? club.description.substring(0, maxLength) + '...' : club.description;
                
                return `
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-500 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900">${club.name}</h4>
                                <p class="text-sm text-gray-600 mt-1">${club.department}</p>
                                <p class="text-xs text-gray-500 mt-1">${club.club_type}</p>
                                ${club.description ? `
                                    <div class="mt-2">
                                        <p id="desc-${index}" class="text-sm text-gray-600 break-words">
                                            ${truncatedDesc}
                                        </p>
                                        <p id="desc-full-${index}" class="text-sm text-gray-600 break-words hidden">
                                            ${club.description}
                                        </p>
                                        ${hasLongDescription ? `
                                            <button onclick="toggleDescription(${index})" 
                                                    id="toggle-btn-${index}"
                                                    class="text-green-600 hover:text-green-700 text-sm font-medium mt-1 focus:outline-none">
                                                Read More
                                            </button>
                                        ` : ''}
                                    </div>
                                ` : ''}
                            </div>
                            <button onclick="requestToJoin(${club.id}, '${club.name}')"
                                    class="flex-shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium whitespace-nowrap self-start">
                                Request to Join
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleDescription(index) {
            const shortDesc = document.getElementById(`desc-${index}`);
            const fullDesc = document.getElementById(`desc-full-${index}`);
            const toggleBtn = document.getElementById(`toggle-btn-${index}`);
            
            if (shortDesc.classList.contains('hidden')) {
                // Currently showing full, switch to short
                shortDesc.classList.remove('hidden');
                fullDesc.classList.add('hidden');
                toggleBtn.textContent = 'Read More';
            } else {
                // Currently showing short, switch to full
                shortDesc.classList.add('hidden');
                fullDesc.classList.remove('hidden');
                toggleBtn.textContent = 'Read Less';
            }
        }

        function requestToJoin(clubId, clubName) {
            // Redirect to application form
            window.location.href = `/club/club-application/${clubId}`;
        }
    </script>
</body>
</html>
