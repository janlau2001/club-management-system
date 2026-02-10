<div class="w-70 bg-gradient-to-b from-[#29553c] to-[#031a0a] shadow-lg flex flex-col h-full">
    <div class="p-7 border-b-5 border-[#FFE670]">
        <h1 class="text-xl font-bold text-[#FFE670]">Club and Organization Management System</h1>
    </div>

    <nav class="mt-6 flex-1">
        <div class="px-3 space-y-2">
            @if(session('admin_role') === 'head_student_affairs')
                <!-- Head of Student Affairs Navigation -->
                <a href="{{ route('head-office.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.dashboard') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('head-office.organizations') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.organizations*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Organizations
                </a>

                <a href="{{ route('head-office.renewals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.renewals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Renewals
                </a>

                <a href="{{ route('head-office.approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registration Monitoring
                </a>

                <a href="{{ route('head-office.members') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.members') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Members
                </a>

                <a href="{{ route('head-office.reports') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.reports') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reports
                </a>

                <!-- Decision Support Section -->
                <div class="mt-6 pt-4 border-t border-[#FFE670]/20">
                    <p class="text-xs text-[#FFE670]/60 uppercase tracking-wider px-4 mb-2">Decision Support</p>
                    
                    <a href="{{ route('head-office.decision-support') }}"
                       class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('head-office.decision-support*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Violation Analysis
                    </a>
                </div>

            @elseif(session('admin_role') === 'director_student_affairs')
                <!-- Director Navigation -->
                <a href="{{ route('director.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('director.dashboard') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('director.approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('director.approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registration Approvals
                </a>

                <a href="{{ route('director.renewal-approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('director.renewal-approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Renewal Approvals
                </a>

            @elseif(session('admin_role') === 'vp_academics')
                <!-- VP Academics Navigation -->
                <a href="{{ route('vp.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('vp.dashboard') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('vp.approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('vp.approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registration Approvals
                </a>

                <a href="{{ route('vp.renewal-approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('vp.renewal-approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Renewal Approvals
                </a>

            @elseif(session('admin_role') === 'dean')
                <!-- Dean Navigation -->
                <a href="{{ route('dean.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('dean.dashboard') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('dean.approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('dean.approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registration Approvals
                </a>

                <a href="{{ route('dean.renewal-approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('dean.renewal-approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Renewal Approvals
                </a>
            @elseif(session('admin_role') === 'psg_council_adviser')
                <!-- PSG Council Adviser Navigation -->
                <a href="{{ route('psg-council.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('psg-council.dashboard') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('psg-council.approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('psg-council.approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registration Approvals
                </a>

                <a href="{{ route('psg-council.renewal-approvals') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-[#FFE670] duration-300 {{ request()->routeIs('psg-council.renewal-approvals*') ? 'bg-[#FFE670] font-bold text-black border-r-8 border-[#FFB726]' : 'text-white hover:text-black' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Renewal Approvals
                </a>
            @endif
        </div>
    </nav>

    <!-- Admin Profile Section -->
    <div class="p-4 border-t border-[#FFE670]/20 mt-auto">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-[#FFE670]/10 transition-colors duration-300 text-white">
                <div class="w-10 h-10 bg-[#FFE670] rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-[#29553c]">{{ strtoupper(substr(session('user.email', 'A'), 0, 1)) }}</span>
                </div>
                <div class="flex-1 text-left">
                    <p class="text-sm font-medium text-[#FFE670]">
                        @if(session('admin_role') === 'head_student_affairs')
                            Head of Student Affairs
                        @elseif(session('admin_role') === 'director_student_affairs')
                            Director
                        @elseif(session('admin_role') === 'vp_academics')
                            VP Academics
                        @elseif(session('admin_role') === 'dean')
                            Dean
                        @elseif(session('admin_role') === 'psg_council_adviser')
                            PSG Council Adviser
                        @else
                            Admin User
                        @endif
                    </p>
                    <p class="text-xs text-white/70">{{ session('user.email', 'admin@club.com') }}</p>
                </div>
                <svg class="w-4 h-4 text-white/70 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" x-transition class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                <a href="{{ route('admin.profile') }}" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <div class="border-t border-gray-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


