<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $club->name }} - Member Dashboard</title>
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
                    <p class="text-white opacity-75 text-sm">Welcome, {{ $clubUser->name }} (Member)</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Mailbox -->
                    <div class="relative" x-data="{ 
                        open: false, 
                        notifications: [],
                        unreadCount: 0,
                        loading: false,
                        hasError: false,
                        
                        async fetchNotifications() {
                            // Don't fetch if a dropdown is open to prevent UI interference
                            if (this.open) return;
                            
                            this.loading = true;
                            this.hasError = false;
                            try {
                                const controller = new AbortController();
                                const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
                                
                                const response = await fetch('{{ route('club.notifications.index') }}', {
                                    signal: controller.signal,
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    }
                                });
                                
                                clearTimeout(timeoutId);
                                
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}`);
                                }
                                
                                const data = await response.json();
                                this.notifications = data.notifications || [];
                                this.unreadCount = data.unread_count || 0;
                            } catch (error) {
                                if (error.name !== 'AbortError') {
                                    console.warn('Notifications fetch failed:', error.message);
                                    this.hasError = true;
                                }
                                // Keep existing data on error, don't reset
                            }
                            this.loading = false;
                        },
                        
                        async markAsRead(notificationId, event) {
                            // Prevent event bubbling
                            if (event) {
                                event.stopPropagation();
                            }
                            
                            try {
                                const response = await fetch(`/club/notifications/${notificationId}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });
                                
                                if (response.ok) {
                                    // Update local state immediately for better UX
                                    const notification = this.notifications.find(n => n.id === notificationId);
                                    if (notification && !notification.is_read) {
                                        notification.is_read = true;
                                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                                    }
                                    
                                    // Optionally refresh in background
                                    setTimeout(() => this.fetchNotifications(), 1000);
                                }
                            } catch (error) {
                                console.error('Error marking as read:', error);
                            }
                        },
                        
                        async markAllAsRead() {
                            try {
                                const response = await fetch('/club/notifications/mark-all-read', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });
                                
                                if (response.ok) {
                                    // Update local state immediately
                                    this.notifications.forEach(n => n.is_read = true);
                                    this.unreadCount = 0;
                                    
                                    // Refresh in background
                                    setTimeout(() => this.fetchNotifications(), 1000);
                                }
                            } catch (error) {
                                console.error('Error marking all as read:', error);
                            }
                        },
                        
                        togglePanel() {
                            this.open = !this.open;
                            if (this.open && this.notifications.length === 0 && !this.hasError) {
                                // Only fetch when opening panel if we don't have data
                                this.fetchNotifications();
                            }
                        },
                        
                        closePanel() {
                            this.open = false;
                        },
                        
                        init() {
                            // Delay initial fetch to avoid interfering with page load
                            setTimeout(() => {
                                this.fetchNotifications();
                            }, 2000);
                            
                            // Set up periodic refresh (less frequent)
                            setInterval(() => {
                                // Only auto-refresh when panel is closed and page is visible
                                if (!this.open && !document.hidden) {
                                    this.fetchNotifications();
                                }
                            }, 60000); // Reduced to 60 seconds
                            
                            // Handle page visibility changes
                            document.addEventListener('visibilitychange', () => {
                                if (!document.hidden && !this.open) {
                                    // Refresh when page becomes visible again
                                    setTimeout(() => this.fetchNotifications(), 1000);
                                }
                            });
                        }
                    }">
                        <button @click="togglePanel()" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30 shadow-lg relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Mailbox</span>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-2 -right-2 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"></span>
                            <span x-show="hasError && !loading" class="absolute -top-1 -right-1 h-3 w-3 bg-yellow-500 rounded-full" title="Connection issue"></span>
                        </button>
                        
                        <div x-show="open" @click.outside="closePanel()" x-transition class="absolute right-0 top-full mt-2 w-96 bg-white rounded-lg shadow-xl border z-50">
                            <!-- Mailbox Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-lg">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <h3 class="text-lg font-semibold">Head Office Mail</h3>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm" x-text="unreadCount > 0 ? unreadCount + ' new messages' : 'No new messages'"></span>
                                        <button @click="closePanel()" class="text-white/80 hover:text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mailbox Content -->
                            <div class="p-4">
                                <div x-show="loading" class="py-6 flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                </div>
                                
                                <div x-show="!loading && notifications.length === 0 && !hasError" class="py-8 flex flex-col items-center justify-center text-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">No Mail</h4>
                                    <p class="text-sm text-gray-500">Your mailbox is empty. You'll receive official messages from the Head Office here.</p>
                                </div>
                                
                                <div x-show="!loading && hasError" class="py-8 flex flex-col items-center justify-center text-center">
                                    <svg class="w-12 h-12 text-yellow-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">Connection Issue</h4>
                                    <p class="text-sm text-gray-500">Unable to load messages. Check your internet connection and try again.</p>
                                    <button @click="fetchNotifications()" class="mt-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                        Retry
                                    </button>
                                </div>
                                
                                <div x-show="!loading && notifications.length > 0" class="max-h-80 overflow-y-auto space-y-3">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="border rounded-lg overflow-hidden transition-all duration-200"
                                             :class="notification.is_read ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-300 shadow-sm'">
                                            <!-- Mail Header -->
                                            <div class="p-3 border-b" :class="notification.is_read ? 'border-gray-200' : 'border-blue-200'">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900">Head Office</p>
                                                            <p class="text-xs text-gray-500" x-text="new Date(notification.created_at).toLocaleDateString() + ' ' + new Date(notification.created_at).toLocaleTimeString()"></p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <div x-show="!notification.is_read" class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                                        <button x-show="!notification.is_read" 
                                                                @click="markAsRead(notification.id, $event)"
                                                                class="text-xs text-blue-600 hover:text-blue-800 underline">
                                                            Mark Read
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Mail Content -->
                                            <div class="p-3">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-2" x-text="notification.title"></h4>
                                                <p class="text-sm text-gray-700" x-text="notification.message"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="!loading && notifications.length > 0 && unreadCount > 0" class="mt-4 pt-4 border-t">
                                    <button @click="markAllAsRead()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                                        Mark All Messages as Read
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors border border-white/30 shadow-lg">
                            <div class="w-8 h-8 bg-white/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $clubUser->name }}</p>
                                <p class="text-xs opacity-75">Member</p>
                            </div>
                            <svg class="w-4 h-4 text-white" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                            <!-- Profile Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ $clubUser->name }}</p>
                                <p class="text-sm text-gray-500">{{ $clubUser->email }}</p>
                                <p class="text-xs text-gray-400">{{ $clubUser->year_level }} • {{ $clubUser->department }}</p>
                            </div>

                            <!-- Menu Items -->
                            <a href="{{ route('club.member.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Profile
                            </a>

                            <form method="POST" action="{{ route('club.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Club Suspension Warning -->
                @if($club->status === 'suspended')
                    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-red-800">Club Currently Suspended</h3>
                                <p class="text-red-700 mt-1">
                                    <strong>{{ $club->name }}</strong> is currently suspended by the administration. 
                                    Access to the club system is temporarily restricted.
                                </p>
                                <p class="text-red-600 text-sm mt-2">
                                    Please contact your club President, Vice President, or Adviser for more information about the suspension.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Members</p>
                                <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalMembers }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Officers</p>
                                <p class="text-3xl font-bold text-green-800 mt-2">{{ $totalOfficers }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Members</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $onlineMembersCount }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Officers</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $onlineOfficersCount }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column - Club News & Activities -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Club News Section -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Club News</h2>
                                <p class="text-gray-600 mt-1">Latest updates and announcements</p>
                            </div>
                            
                            <div class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No News Yet</h3>
                                    <p class="text-gray-500">Club news and announcements will appear here.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Club Activities Section -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Club Activities</h2>
                                <p class="text-gray-600 mt-1">Upcoming events and activities</p>
                            </div>
                            
                            <div class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Scheduled</h3>
                                    <p class="text-gray-500">Club activities and events will be displayed here.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Member Actions & Online Status -->
                    <div class="space-y-8">

                        <!-- Member Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Member Actions</h2>
                                <p class="text-gray-600 mt-1">What you can do</p>
                            </div>

                            <div class="p-6 space-y-3">
                            <div class="p-6 space-y-3">
                                <a href="{{ route('club.member.view-members') }}"
                                   class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span>View Members</span>
                                    </div>
                                </a>                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-gray-700">Member Access</p>
                                            <p class="text-xs text-gray-600">You can view club information and members</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Online Officers -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Officers</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineOfficersCount }} online</p>
                            </div>
                            
                            <div class="p-6">
                                @if($onlineOfficers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineOfficers as $officer)
                                            <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">{{ $officer->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $officer->position ?? 'Officer' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">No officers online</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Online Members -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Members</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineMembersCount }} online</p>
                            </div>
                            
                            <div class="p-6 max-h-96 overflow-y-auto">
                                @if($onlineMembers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineMembers as $member)
                                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $member->year_level }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-4">No members online</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Auto-refresh online status every 30 seconds -->
    <script>
        setInterval(function() {
            fetch('/club/refresh-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            }).catch(() => {
                // Ignore errors
            });
        }, 30000);
    </script>
</body>
</html>
