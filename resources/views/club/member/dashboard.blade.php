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
        <header class="p-4 bg-gradient-to-r from-[#29553c] to-[#031a0a]">
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
                                    this.notifications.forEach(n => n.is_read = true);
                                    this.unreadCount = 0;
                                    setTimeout(() => this.fetchNotifications(), 1000);
                                }
                            } catch (error) {}
                        },

                        async clearAll() {
                            try {
                                const response = await fetch('/club/notifications/clear-all', {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                });
                                if (response.ok) {
                                    this.notifications = [];
                                    this.unreadCount   = 0;
                                }
                            } catch (error) {}
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
                        <button @click="togglePanel()" class="relative flex items-center justify-center w-10 h-10 bg-white/20 hover:bg-white/30 text-white transition-colors border border-white/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-0.5 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center leading-none"></span>
                            <span x-show="hasError && !loading" class="absolute -top-1 -right-1 h-3 w-3 bg-yellow-500" title="Connection issue"></span>
                        </button>
                        
                        <div x-show="open" @click.outside="closePanel()" x-transition class="absolute right-0 top-full mt-2 w-96 bg-white border z-50">
                            <!-- Panel Header -->
                            <div class="bg-gray-900 text-white px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span class="text-sm font-semibold">Notifications</span>
                                    <span x-show="unreadCount > 0" x-text="unreadCount + ' unread'"
                                          class="text-xs bg-red-500 text-white px-1.5 py-0.5 font-medium"></span>
                                </div>
                                <button @click="closePanel()" class="text-white/70 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Mailbox Content -->
                            <div class="p-4">
                                <div x-show="loading" class="py-6 flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                </div>
                                
                                <div x-show="!loading && notifications.length === 0 && !hasError" class="py-10 text-center">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">No notifications</p>
                                    <p class="text-xs text-gray-400 mt-1">You'll see reminders and updates here.</p>
                                </div>
                                
                                <div x-show="!loading && hasError" class="py-8 flex flex-col items-center justify-center text-center">
                                    <svg class="w-12 h-12 text-yellow-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">Connection Issue</h4>
                                    <p class="text-sm text-gray-500">Unable to load messages. Check your internet connection and try again.</p>
                                    <button @click="fetchNotifications()" class="mt-3 px-4 py-2 bg-blue-600 text-white text-sm hover:bg-blue-700">
                                        Retry
                                    </button>
                                </div>
                                
                                <div x-show="!loading && notifications.length > 0" class="max-h-80 overflow-y-auto space-y-2">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="border p-3 transition-colors"
                                             :class="notification.is_read ? 'bg-white border-gray-100' : 'bg-amber-50 border-amber-200'">
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <p class="text-xs font-semibold text-gray-900" x-text="notification.title"></p>
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span x-show="!notification.is_read" class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    <button x-show="!notification.is_read"
                                                            @click="markAsRead(notification.id, $event)"
                                                            class="text-[10px] text-gray-500 hover:text-gray-700 underline">
                                                        Mark read
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-600 leading-relaxed" x-text="notification.message"></p>
                                            <p class="text-[10px] text-gray-400 mt-1.5" x-text="new Date(notification.created_at).toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'})"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="!loading && notifications.length > 0" class="mt-3 pt-3 border-t border-gray-100 flex gap-2">
                                    <button x-show="unreadCount > 0" @click="markAllAsRead()"
                                            class="flex-1 text-xs py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                        Mark All Read
                                    </button>
                                    <button @click="clearAll()"
                                            class="flex-1 text-xs py-2 bg-red-600 hover:bg-red-700 text-white transition-colors font-medium">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 text-white px-3 py-2 text-sm font-medium transition-colors border border-white/30">
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
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 py-2 z-50">

                            <!-- Profile Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ $clubUser->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $clubUser->email }}</p>
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
                    <div class="bg-red-50 border border-red-200 p-6">
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
                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Members</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMembers }}</p>
                            </div>
                            <div class="bg-gray-100 p-3">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Officers</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOfficers }}</p>
                            </div>
                            <div class="bg-gray-100 p-3">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Members</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $onlineMembersCount }}</p>
                            </div>
                            <div class="bg-gray-100 p-3">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Online Officers</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $onlineOfficersCount }}</p>
                            </div>
                            <div class="bg-gray-100 p-3">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Club News</h2>
                                <p class="text-gray-600 mt-1">Latest updates and announcements</p>
                            </div>
                            
                            <div class="p-6">
                                @if($clubNews->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($clubNews as $news)
                                            <div class="border border-gray-200 overflow-hidden">
                                                @if($news->image)
                                                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">
                                                @endif
                                                <div class="p-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $news->title }}</h3>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        By {{ $news->author->name ?? 'Unknown' }} &bull; {{ $news->published_at->format('M d, Y') }}
                                                    </p>
                                                    <p class="text-gray-700 mt-2 text-sm">{{ $news->description }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No News Yet</h3>
                                        <p class="text-gray-500">Club news and announcements will appear here.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Club Activities Section -->
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Club Activities</h2>
                                <p class="text-gray-600 mt-1">Upcoming events and activities</p>
                            </div>
                            
                            <div class="p-6">
                                @if($clubActivities->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($clubActivities as $activity)
                                            <div class="border border-gray-200 p-4">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $activity->scheduled_at->format('M d, Y \a\t g:i A') }}
                                                </p>
                                                <p class="text-gray-700 mt-2 text-sm">{{ $activity->description }}</p>
                                                @if($activity->scheduled_at->isFuture())
                                                    <span class="inline-block mt-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700">Upcoming</span>
                                                @else
                                                    <span class="inline-block mt-2 px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">Past</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Scheduled</h3>
                                        <p class="text-gray-500">Club activities and events will be displayed here.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Member Actions & Online Status -->
                    <div class="space-y-8">

                        <!-- Member Actions -->
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Member Actions</h2>
                                <p class="text-gray-600 mt-1">What you can do</p>
                            </div>

                            <div class="p-6 space-y-3">
                                <a href="{{ route('club.member.view-members') }}"
                                   class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 text-sm font-medium transition-colors text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span>View Members</span>
                                    </div>
                                </a>                                <div class="bg-gray-50 border border-gray-200 p-3">
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
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Officers</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineOfficersCount }} online</p>
                            </div>
                            
                            <div class="p-6">
                                @if($onlineOfficers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineOfficers as $officer)
                                            <div class="flex items-center space-x-3 p-3 bg-gray-50">
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
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900">Online Members</h2>
                                <p class="text-gray-600 mt-1">{{ $onlineMembersCount }} online</p>
                            </div>
                            
                            <div class="p-6 max-h-96 overflow-y-auto">
                                @if($onlineMembers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($onlineMembers as $member)
                                            <div class="flex items-center space-x-3 p-3 bg-gray-50">
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
